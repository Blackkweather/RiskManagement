<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'lang/translation.php';
require_once 'config/database.php';

$risk_coverage = 0;  // Initialize risk_coverage to avoid undefined variable warning
$avg_control_effectiveness = 0; // Initialize avg_control_effectiveness to avoid undefined variable warning

// Initialize all statistics variables
$total_risks = 0;
$high_risks = 0;
$medium_risks = 0;
$low_risks = 0;
$total_entities = 0;
$total_categories = 0;
$total_controls = 0;
$high_priority_risks = 0;  // Added for stats cards

// Initialize arrays
$risk_trends = [];
$risk_by_category = [];
$mitigation_effectiveness = [];
$project_risk_distribution = [];
$control_effectiveness = [];

try {
    $db = new Database();
    $conn = $db->getConnection();
    $current_year = date('Y');

    if ($conn) {
        // Calculate all risk statistics in one query
        $statsQuery = "
            SELECT 
                COUNT(*) as total_risks,
                SUM(CASE WHEN brutCriticality >= 18 THEN 1 ELSE 0 END) as high_risks,
                SUM(CASE WHEN brutCriticality >= 12 AND brutCriticality < 18 THEN 1 ELSE 0 END) as medium_risks,
                SUM(CASE WHEN brutCriticality < 12 THEN 1 ELSE 0 END) as low_risks,
                COUNT(DISTINCT entityId) as total_entities,
                COUNT(DISTINCT riskFamilyId) as total_categories,
                SUM(CASE WHEN priority = 'high' OR brutCriticality >= 18 THEN 1 ELSE 0 END) as high_priority_risks
            FROM Risk 
            WHERE active = 1";
        
        if ($stmt = $conn->query($statsQuery)) {
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            $total_risks = intval($stats['total_risks']);
            $high_risks = intval($stats['high_risks']);
            $medium_risks = intval($stats['medium_risks']);
            $low_risks = intval($stats['low_risks']);
            $total_entities = intval($stats['total_entities']);
            $total_categories = intval($stats['total_categories']);
            $high_priority_risks = intval($stats['high_priority_risks']);  // Populate high priority risks
        }

        // Calculate risk coverage (percentage of risks with controls)
        $risk_coverage_query = "
            SELECT 
                ROUND(
                    (COUNT(DISTINCT CASE WHEN rc.controlId IS NOT NULL THEN r.id END) * 100.0) / 
                    NULLIF(COUNT(DISTINCT r.id), 0)
                ) as coverage_percentage,
                COUNT(DISTINCT r.id) as total_risks,
                COUNT(DISTINCT CASE WHEN rc.controlId IS NOT NULL THEN r.id END) as risks_with_controls
            FROM Risk r
            LEFT JOIN RiskControl rc ON r.id = rc.riskId
            WHERE r.active = 1";
        
        if ($stmt = $conn->query($risk_coverage_query)) {
            $coverage_result = $stmt->fetch(PDO::FETCH_ASSOC);
            $risk_coverage = $coverage_result['coverage_percentage'] ?? 0;
            
            // Debug output
            error_log("Risk Coverage Debug:");
            error_log("Total Risks: " . $coverage_result['total_risks']);
            error_log("Risks with Controls: " . $coverage_result['risks_with_controls']);
            error_log("Coverage Percentage: " . $risk_coverage);
        } else {
            $risk_coverage = 0;
            error_log("Risk Coverage Query Failed: " . $conn->errorInfo()[2]);
        }

        // Calculate average control effectiveness
        $effectiveness_query = "
            SELECT 
                ROUND(
                    AVG(CASE 
                        WHEN rc.effectiveness IS NOT NULL THEN rc.effectiveness 
                        ELSE 0 
                    END)
                ) as avg_effectiveness,
                COUNT(rc.effectiveness) as total_controls_with_effectiveness
            FROM Risk r
            LEFT JOIN RiskControl rc ON r.id = rc.riskId
            WHERE r.active = 1";

        if ($stmt = $conn->query($effectiveness_query)) {
            $effectiveness_result = $stmt->fetch(PDO::FETCH_ASSOC);
            $avg_control_effectiveness = $effectiveness_result['avg_effectiveness'] ?? 0;
            
            // Debug output
            error_log("Control Effectiveness Debug:");
            error_log("Total Controls with Effectiveness: " . $effectiveness_result['total_controls_with_effectiveness']);
            error_log("Average Effectiveness: " . $avg_control_effectiveness);
        } else {
            $avg_control_effectiveness = 0;
            error_log("Effectiveness Query Failed: " . $conn->errorInfo()[2]);
        }
            
        // Get risk trends by month
        $stmt = $conn->prepare("
            SELECT 
                MONTH(createdAt) as month,
                CASE 
                    WHEN brutCriticality >= 18 THEN 'high'
                    WHEN brutCriticality >= 12 THEN 'medium'
                    ELSE 'low'
                END as risk_level,
                COUNT(*) as count
            FROM Risk
            WHERE YEAR(createdAt) = ? AND active = 1
            GROUP BY MONTH(createdAt), 
                CASE 
                    WHEN brutCriticality >= 18 THEN 'high'
                    WHEN brutCriticality >= 12 THEN 'medium'
                    ELSE 'low'
                END
            ORDER BY month");
        
        $stmt->execute([$current_year]);
        
        // Initialize months
        $months = ['January', 'February', 'March', 'April', 'May', 'June'];
        foreach ($months as $month) {
            $risk_trends[$month] = ['high' => 0, 'medium' => 0, 'low' => 0];
        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $month_name = date('F', mktime(0, 0, 0, $row['month'], 1));
            if (in_array($month_name, $months)) {
                $risk_trends[$month_name][$row['risk_level']] = intval($row['count']);
            }
        }

        // Get risks by category
        $stmt = $conn->query("
            SELECT 
                COALESCE(rf.name, 'Non catégorisé') as category,
                COUNT(*) as count
            FROM Risk r
            LEFT JOIN RiskFamily rf ON r.riskFamilyId = rf.id
            WHERE r.active = 1
            GROUP BY rf.name");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $risk_by_category[$row['category']] = intval($row['count']);
        }

        // Get mitigation effectiveness
        $stmt = $conn->query("
            SELECT 
                CASE 
                    WHEN netCriticality <= brutCriticality * 0.3 THEN 'Entièrement Atténué'
                    WHEN netCriticality <= brutCriticality * 0.6 THEN 'Partiellement Atténué'
                    WHEN netCriticality <= brutCriticality * 0.8 THEN 'En Révision'
                    ELSE 'Non Atténué'
                END as effectiveness,
                COUNT(*) as count
            FROM Risk
            WHERE active = 1
            GROUP BY effectiveness");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $mitigation_effectiveness[$row['effectiveness']] = intval($row['count']);
        }

        // Get project risk distribution
        $stmt = $conn->query("
            SELECT 
                p.name as project_name,
                COUNT(r.id) as total_risks,
                SUM(CASE WHEN r.brutCriticality >= 18 THEN 1 ELSE 0 END) as high_priority
            FROM Project p
            LEFT JOIN Entity e ON e.projectId = p.id
            LEFT JOIN Risk r ON r.entityId = e.id
            WHERE p.active = 1
            GROUP BY p.id, p.name");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $project_risk_distribution[$row['project_name']] = [
                'risks' => intval($row['total_risks']),
                'high_priority' => intval($row['high_priority'])
            ];
        }

        // Get control effectiveness
        $stmt = $conn->query("
            SELECT 
                COUNT(*) as count
            FROM RiskControl
            WHERE active = 1");
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $control_effectiveness['Préventif'] = intval($row['count']);

        // Calculate total statistics
        $stmt = $conn->query("
            SELECT 
                COUNT(*) as total_risks,
                SUM(CASE WHEN brutCriticality >= 18 THEN 1 ELSE 0 END) as high_risks,
                SUM(CASE WHEN brutCriticality >= 12 AND brutCriticality < 18 THEN 1 ELSE 0 END) as medium_risks,
                SUM(CASE WHEN brutCriticality < 12 THEN 1 ELSE 0 END) as low_risks,
                COUNT(DISTINCT entityId) as total_entities,
                COUNT(DISTINCT riskFamilyId) as total_categories
            FROM Risk 
            WHERE active = 1");
        
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_risks = intval($stats['total_risks']);
        $high_risks = intval($stats['high_risks']);
        $medium_risks = intval($stats['medium_risks']);
        $low_risks = intval($stats['low_risks']);
        $total_entities = intval($stats['total_entities']);
        $total_categories = intval($stats['total_categories']);

        // Get total controls
        $stmt = $conn->query("SELECT COUNT(*) as total_controls FROM RiskControl WHERE active = 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_controls = intval($row['total_controls']);
    }

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $error_message = "Une erreur est survenue lors de la récupération des données.";
}

// Ensure all arrays are initialized with default values if empty
if (empty($risk_by_category)) {
    $risk_by_category = [
        'Opérationnel' => 0,
        'Financier' => 0,
        'Stratégique' => 0,
        'Conformité' => 0,
        'Technologique' => 0,
        'Réputationnel' => 0
    ];
}

if (empty($mitigation_effectiveness)) {
    $mitigation_effectiveness = [
        'Entièrement Atténué' => 0,
        'Partiellement Atténué' => 0,
        'En Révision' => 0,
        'Non Atténué' => 0
    ];
}

if (empty($project_risk_distribution)) {
    $project_risk_distribution = [
        'Project 1' => ['risks' => 0, 'high_priority' => 0],
        'Project 2' => ['risks' => 0, 'high_priority' => 0],
        'Project 3' => ['risks' => 0, 'high_priority' => 0]
    ];
}

if (empty($control_effectiveness)) {
    $control_effectiveness = [
        'Préventif' => 0,
        'Détectif' => 0,
        'Correctif' => 0
    ];
}

// Fetch recent activities for timeline from database
$recent_activities = [];
if ($conn) {
    try {
        $stmt = $conn->query("SELECT type, title, description, timestamp, severity, user FROM recent_activities ORDER BY timestamp DESC LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recent_activities[] = $row;
        }
    } catch (PDOException $e) {
        echo "Error fetching recent activities: " . $e->getMessage();
    }
}

// Fallback to empty array if no data is fetched
$recent_activities = !empty($recent_activities) ? $recent_activities : [
    [
        'type' => 'info',
        'title' => 'No Recent Activity',
        'description' => 'No recent activities found in the database.',
        'timestamp' => date('Y-m-d H:i'),
        'severity' => 'low',
        'user' => 'System'
    ]
];

// Top risks by criticality from database
$top_risks = [];
if ($conn) {
    try {
        $stmt = $conn->query("
            SELECT name, brutCriticality as score, 'stable' as trend
            FROM Risk
            WHERE active = 1
            ORDER BY brutCriticality DESC
            LIMIT 5
        ");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $top_risks[] = $row;
        }
    } catch (PDOException $e) {
        echo "Error fetching top risks: " . $e->getMessage();
    }
}

// Fallback to empty array if no data is fetched
$top_risks = !empty($top_risks) ? $top_risks : [
    ['name' => 'No Data Available', 'score' => 0, 'trend' => 'stable']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - RiskGuard Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #3b82f6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f1f5f9;
            --gray: #64748b;
            --white: #ffffff;
            --border: #e2e8f0;
            --card-bg: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #334155;
            min-height: 100vh;
        }

        #app {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, var(--dark) 0%, #0f172a 100%);
            color: white;
            padding: 0;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-lg);
            z-index: 100;
            position: relative;
        }

        .logo {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
        }

        .logo i {
            font-size: 28px;
            color: var(--secondary);
            background: rgba(59, 130, 246, 0.2);
            padding: 8px;
            border-radius: 8px;
        }

        .logo span {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        nav {
            flex: 1;
            padding: 24px 0;
        }

        nav ul {
            list-style: none;
        }

        nav ul li {
            margin: 4px 16px;
        }

        nav ul li a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            font-size: 15px;
            font-weight: 500;
            border-radius: 10px;
            position: relative;
        }

        nav ul li a:hover {
            background: rgba(59, 130, 246, 0.15);
            color: white;
            transform: translateX(4px);
        }

        nav ul li a.active {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        nav ul li a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .user-info {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.05);
        }

        .user-info img {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .user-info div {
            line-height: 1.4;
        }

        .user-info strong {
            font-size: 15px;
            display: block;
            font-weight: 600;
        }

        .user-info small {
            font-size: 13px;
            color: rgba(255,255,255,0.7);
        }

        /* Header Styles */
        .header {
            background: var(--white);
            padding: 0 32px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow);
            z-index: 99;
            border-bottom: 1px solid var(--border);
        }

        .header-left h1 {
            font-size: 24px;
            color: var(--dark);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .time-filter {
            display: flex;
            background: var(--light);
            border-radius: 10px;
            padding: 4px;
        }

        .time-filter button {
            padding: 8px 16px;
            border: none;
            background: transparent;
            color: var(--gray);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 14px;
        }

        .time-filter button.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
        }

        .refresh-btn {
            padding: 10px;
            background: var(--light);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            color: var(--gray);
            transition: all 0.3s ease;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .refresh-btn:hover {
            background: var(--border);
            color: var(--dark);
        }

        /* Main Content */
        .main-content {
            padding: 32px;
            overflow-y: auto;
            background: transparent;
        }

        /* KPI Cards */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .kpi-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 28px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .kpi-card.primary::before {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .kpi-card.danger::before {
            background: linear-gradient(90deg, var(--danger), #dc2626);
        }

        .kpi-card.success::before {
            background: linear-gradient(90deg, var(--success), #059669);
        }

        .kpi-card.warning::before {
            background: linear-gradient(90deg, var(--warning), #d97706);
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .kpi-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .kpi-title {
            font-size: 14px;
            color: var(--gray);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .kpi-icon.primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .kpi-icon.danger {
            background: linear-gradient(135deg, var(--danger), #dc2626);
        }

        .kpi-icon.success {
            background: linear-gradient(135deg, var(--success), #059669);
        }

        .kpi-icon.warning {
            background: linear-gradient(135deg, var(--warning), #d97706);
        }

        .kpi-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--dark);
            line-height: 1;
            margin-bottom: 8px;
        }

        .kpi-change {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 500;
        }

        .kpi-change.positive {
            color: var(--success);
        }

        .kpi-change.negative {
            color: var(--danger);
        }

        .kpi-change.neutral {
            color: var(--gray);
        }

        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Card Styles */
        .card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .card-header {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #fafbfc 0%, #f1f5f9 100%);
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.3px;
        }

        .card-body {
            padding: 28px;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        .chart-container.small {
            height: 200px;
        }

        /* Activity Timeline */
        .activity-timeline {
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid var(--border);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
            flex-shrink: 0;
        }

        .activity-icon.high {
            background: var(--danger);
        }

        .activity-icon.medium {
            background: var(--warning);
        }

        .activity-icon.low {
            background: var(--success);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .activity-description {
            font-size: 14px;
            color: var(--gray);
            margin-bottom: 8px;
        }

        .activity-meta {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: var(--gray);
        }

        /* Top Risks List */
        .risk-list {
            padding: 0;
        }

        .risk-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid var(--border);
        }

        .risk-item:last-child {
            border-bottom: none;
        }

        .risk-info {
            flex: 1;
        }

        .risk-name {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .risk-score {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .score-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--danger);
        }

        .trend-indicator {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .trend-indicator.up {
            color: var(--danger);
        }

        .trend-indicator.down {
            color: var(--success);
        }

        .trend-indicator.stable {
            color: var(--gray);
        }

        /* Progress Bars */
        .progress-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .progress-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .progress-label {
            font-weight: 600;
            color: var(--dark);
        }

        .progress-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
        }

        .progress-bar {
            height: 8px;
            background: var(--light);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            #app {
                grid-template-columns: 1fr;
            }
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                transition: left 0.3s ease;
                z-index: 1000;
                width: 280px;
            }
            .sidebar.active {
                left: 0;
            }
            .header {
                padding-left: 80px;
            }
            .menu-toggle {
                display: flex;
                position: fixed;
                top: 24px;
                left: 24px;
                z-index: 999;
                background: var(--primary);
                color: white;
                width: 44px;
                height: 44px;
                border-radius: 12px;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: var(--shadow);
                transition: all 0.3s ease;
            }
            .menu-toggle:hover {
                transform: scale(1.05);
            }
            .charts-grid {
                grid-template-columns: 1fr;
            }
            .charts-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .kpi-grid {
                grid-template-columns: 1fr 1fr;
            }
            .main-content {
                padding: 20px;
            }
            .header {
                padding: 0 20px 0 80px;
            }
            .header-right {
                flex-direction: column;
                gap: 8px;
            }
        }

        .menu-toggle {
            display: none;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="menu-toggle">
            <i class="fas fa-bars"></i>
        </div>
        
        <aside class="sidebar">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
                <span>RiskGuard Pro</span>
            </div>
            
            <nav>
                <ul>
                    <li><a href="index.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                    <li><a href="clients.php"><i class="fas fa-building"></i> Clients</a></li>
                    <li><a href="projects.php"><i class="fas fa-project-diagram"></i> Projects</a></li>
                    <li><a href="entities.php"><i class="fas fa-sitemap"></i> Entities</a></li>
                    <li><a href="processes.php"><i class="fas fa-cogs"></i> Processes</a></li>
                    <li><a href="risks.php"><i class="fas fa-exclamation-triangle"></i> Risks</a></li>
                    <li><a href="risk_matrix.php"><i class="fas fa-th"></i> Risk Matrix</a></li>
                    <li><a href="controls.php"><i class="fas fa-shield-check"></i> Controls</a></li>
                    <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
                    <li><a href="analytics.php" class="active"><i class="fas fa-chart-bar"></i> Analytics</a></li>
                </ul>
            </nav>
            
            <div class="user-info">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face" alt="User">
                <div>
                    <strong>John Doe</strong>
                    <small>Risk Manager</small>
                </div>
            </div>
        </aside>

        <main>
            <header class="header">
                <div class="header-left">
                    <h1><i class="fas fa-chart-bar"></i> <?php echo __('Analytics'); ?></h1>
                </div>
                <div class="header-right">
                    <div class="time-filter">
                        <button class="active" data-period="7d">7J</button>
                        <button data-period="30d">30J</button>
                        <button data-period="90d">90J</button>
                        <button data-period="1y">1A</button>
                    </div>
                    <button class="refresh-btn" onclick="refreshData()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </header>

            <div class="main-content">
                <!-- KPI Cards -->
                <div class="kpi-grid">
                    <div class="kpi-card primary">
                        <div class="kpi-header">
                            <div class="kpi-title"><?php echo __('Total Risks'); ?></div>
                            <div class="kpi-icon primary">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="kpi-value"><?php echo $total_risks; ?></div>
                        <div class="kpi-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+12% par rapport au mois dernier</span>
                        </div>
                    </div>
                    
                    <div class="kpi-card danger">
                        <div class="kpi-header">
                            <div class="kpi-title"><?php echo __('High Priority'); ?></div>
                            <div class="kpi-icon danger">
                                <i class="fas fa-fire"></i>
                            </div>
                        </div>
                        <div class="kpi-value"><?php echo $high_priority_risks; ?></div>
                        <div class="kpi-change negative">
                            <i class="fas fa-arrow-down"></i>
                            <span>-8% par rapport au mois dernier</span>
                        </div>
                    </div>
                    
                    <div class="kpi-card success">
                        <div class="kpi-header">
                            <div class="kpi-title">Couverture des Risques</div>
                            <div class="kpi-icon success">
                                <i class="fas fa-shield-check"></i>
                            </div>
                        </div>
                        <div class="kpi-value"><?php echo $risk_coverage; ?>%</div>
                        <div class="kpi-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+5% par rapport au mois dernier</span>
                        </div>
                    </div>
                    
                    <div class="kpi-card warning">
                        <div class="kpi-header">
                            <div class="kpi-title"><?php echo __('Effectiveness'); ?></div>
                            <div class="kpi-icon warning">
                                <i class="fas fa-cogs"></i>
                            </div>
                        </div>
                        <div class="kpi-value"><?php echo $avg_control_effectiveness; ?>%</div>
                        <div class="kpi-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+3% par rapport au mois dernier</span>
                        </div>
                    </div>
                </div>

                <!-- Main Charts -->
                <div class="charts-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Tendances des Risques dans le Temps</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="riskTrendsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Activités Récentes</div>
                        </div>
                        <div class="card-body">
                            <div class="activity-timeline">
                                <?php foreach ($recent_activities as $activity): ?>
                                <div class="activity-item">
                                    <div class="activity-icon <?php echo $activity['severity']; ?>">
                                        <i class="fas fa-<?php 
                                            switch($activity['type']) {
                                                case 'risk_created': echo 'plus'; break;
                                                case 'control_updated': echo 'edit'; break;
                                                case 'report_generated': echo 'file-alt'; break;
                                                case 'risk_mitigated': echo 'check'; break;
                                                case 'audit_completed': echo 'clipboard-check'; break;
                                                default: echo 'info'; break;
                                            }
                                        ?>"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title"><?php echo htmlspecialchars($activity['title']); ?></div>
                                        <div class="activity-description"><?php echo htmlspecialchars($activity['description']); ?></div>
                                        <div class="activity-meta">
                                            <span><i class="fas fa-clock"></i> <?php echo date('M j, H:i', strtotime($activity['timestamp'])); ?></span>
                                            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($activity['user']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Charts -->
                <div class="charts-row">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Distribution des Risques par Catégorie</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container small">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">État de l'Atténuation</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container small">
                                <canvas id="mitigationChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section -->
                <div class="charts-row">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Principaux Risques par Criticité</div>
                        </div>
                        <div class="card-body">
                            <div class="risk-list">
                                <?php foreach ($top_risks as $risk): ?>
                                <div class="risk-item">
                                    <div class="risk-info">
                                        <div class="risk-name"><?php echo htmlspecialchars($risk['name']); ?></div>
                                    </div>
                                    <div class="risk-score">
                                        <div class="score-value"><?php echo $risk['score']; ?></div>
                                        <div class="trend-indicator <?php echo $risk['trend']; ?>">
                                            <i class="fas fa-arrow-<?php 
                                                echo $risk['trend'] === 'up' ? 'up' : 
                                                    ($risk['trend'] === 'down' ? 'down' : 'right'); 
                                            ?>"></i>
                                            <span><?php echo ucfirst($risk['trend']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Efficacité des Contrôles par Type</div>
                        </div>
                        <div class="card-body">
                            <div class="progress-list">
                                <?php foreach ($control_effectiveness as $type => $effectiveness): ?>
                                <div class="progress-item">
                                    <div class="progress-header">
                                        <div class="progress-label"><?php echo $type; ?></div>
                                        <div class="progress-value"><?php echo $effectiveness; ?>%</div>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo $effectiveness; ?>%"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Data from PHP
        const riskTrends = <?php echo json_encode($analytics_data['risk_trends']); ?>;
        const riskByCategory = <?php echo json_encode($analytics_data['risk_by_category']); ?>;
        const mitigationEffectiveness = <?php echo json_encode($analytics_data['mitigation_effectiveness']); ?>;

        // Risk Trends Chart
        const trendsCtx = document.getElementById('riskTrendsChart').getContext('2d');
        const riskTrendsChart = new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: Object.keys(riskTrends),
                datasets: [
                    {
                        label: 'High Risk',
                        data: Object.values(riskTrends).map(m => m.high),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Medium Risk',
                        data: Object.values(riskTrends).map(m => m.medium),
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Low Risk',
                        data: Object.values(riskTrends).map(m => m.low),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#334155',
                            font: {
                                weight: 600
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#2563eb',
                        bodyColor: '#334155',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e2e8f0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Time filter functionality
        document.querySelectorAll('.time-filter button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.time-filter button').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                // Here you would typically reload data for the selected period
                console.log('Selected period:', this.dataset.period);
            });
        });

        // Refresh data function
        function refreshData() {
            const refreshBtn = document.querySelector('.refresh-btn i');
            refreshBtn.style.animation = 'spin 1s linear';
            
            // Simulate data refresh
            setTimeout(() => {
                refreshBtn.style.animation = '';
                // Here you would typically reload all charts with fresh data
                console.log('Data refreshed');
            }, 1000);
        }

        // Mobile menu toggle
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Add spin animation for refresh button
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
