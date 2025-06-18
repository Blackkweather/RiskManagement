<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'lang/translation.php';

// Include database connection
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Fetch dashboard stats from database
$stats = [
    'total_clients' => 0,
    'active_projects' => 0,
    'open_risks' => 0,
    'high_priority_risks' => 0
];
$risk_trends = [];
$risk_by_category = [];
if ($conn) {
    $stats['total_clients'] = (int)$conn->query("SELECT COUNT(*) FROM clientprofile")->fetchColumn();
    $stats['active_projects'] = (int)$conn->query("SELECT COUNT(*) FROM project WHERE active = 1")->fetchColumn();
    $stats['open_risks'] = (int)$conn->query("SELECT COUNT(*) FROM risk WHERE active = 1")->fetchColumn();
    $stats['high_priority_risks'] = (int)$conn->query("SELECT COUNT(*) FROM risk WHERE brutCriticality >= 18")->fetchColumn();

    // Get risk trends by month for current year
    $current_year = date('Y');
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
            COALESCE(rf.name, 'Uncategorized') as category,
            COUNT(*) as count
        FROM Risk r
        LEFT JOIN RiskFamily rf ON r.riskFamilyId = rf.id
        WHERE r.active = 1
        GROUP BY rf.name");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $risk_by_category[$row['category']] = intval($row['count']);
    }
}

// Fetch recent high priority risks from database
$recent_risks = [];
if ($conn) {
    $stmt = $conn->query("SELECT r.id, r.code, r.name, p.name AS project_name, r.brutCriticality, r.createdAt FROM risk r LEFT JOIN project p ON r.activityId = p.id WHERE r.brutCriticality >= 15 ORDER BY r.createdAt DESC LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $recent_risks[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('Risk Management Dashboard'); ?> - <?php echo __('RiskGuard Pro'); ?></title>
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
            gap: 24px;
        }

        .notifications {
            position: relative;
            cursor: pointer;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: var(--light);
        }

        .notifications:hover {
            background: var(--border);
            transform: scale(1.05);
        }

        .badge {
            position: absolute;
            top: 6px;
            right: 6px;
            background: var(--danger);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            border: 2px solid white;
        }

        .btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 14px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .btn-primary:hover {
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        /* Main Content */
        .main-content {
            padding: 32px;
            overflow-y: auto;
            background: transparent;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 28px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            font-size: 14px;
            color: var(--gray);
            margin-bottom: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            margin: 8px 0 12px 0;
            color: var(--dark);
            line-height: 1;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 500;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        .stat-change.neutral {
            color: var(--gray);
        }

        /* Card Styles */
        .card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 24px;
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
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.3px;
        }

        .card-body {
            padding: 28px;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 16px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .table th {
            background: var(--light);
            font-weight: 700;
            color: var(--dark);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: rgba(59, 130, 246, 0.04);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-critical {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .status-high {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-medium {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-low {
            background: rgba(100, 116, 139, 0.1);
            color: var(--gray);
            border: 1px solid rgba(100, 116, 139, 0.2);
        }

        .status-open {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .empty-state {
            text-align: center;
            color: var(--gray);
            padding: 60px 40px;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            color: var(--success);
            opacity: 0.7;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: var(--dark);
        }

        .empty-state p {
            font-size: 14px;
            line-height: 1.5;
        }

        /* Charts */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .chart-container {
            height: 300px;
            position: relative;
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
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .charts-grid {
                grid-template-columns: 1fr;
            }
            .main-content {
                padding: 20px;
            }
            .header {
                padding: 0 20px 0 80px;
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
                <span><?php echo __('RiskGuard Pro'); ?></span>
            </div>
            
            <nav>
                <ul>
                    <li><a href="index.php" class="active"><i class="fas fa-chart-line"></i> <?php echo __('Dashboard'); ?></a></li>
                    <li><a href="clients.php"><i class="fas fa-building"></i> <?php echo __('Clients'); ?></a></li>
                    <li><a href="projects.php"><i class="fas fa-project-diagram"></i> <?php echo __('Projects'); ?></a></li>
                    <li><a href="entities.php"><i class="fas fa-sitemap"></i> <?php echo __('Entities'); ?></a></li>
                    <li><a href="processes.php"><i class="fas fa-cogs"></i> <?php echo __('Processes'); ?></a></li>
                    <li><a href="risks.php"><i class="fas fa-exclamation-triangle"></i> <?php echo __('Risks'); ?></a></li>
                    <li><a href="controls.php"><i class="fas fa-shield-check"></i> <?php echo __('Controls'); ?></a></li>
                    <li><a href="reports.php"><i class="fas fa-file-alt"></i> <?php echo __('Reports'); ?></a></li>
                </ul>
            </nav>
            
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=2563eb&color=fff&rounded=true" alt="<?php echo __('Admin User'); ?>">
                <div>
                    <strong><?php echo __('Admin User'); ?></strong>
                    <small><?php echo __('System Administrator'); ?></small>
                </div>
            </div>
        </aside>
        
        <div class="content">
            <header class="header">
                <div class="header-left">
                    <h1><?php echo __('Risk Management Dashboard'); ?></h1>
                </div>
                <div class="header-right">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge"><?php echo $stats['high_priority_risks']; ?></span>
                    </div>
                    <a href="logout.php" class="btn">
                        <i class="fas fa-sign-out-alt"></i> <?php echo __('Logout'); ?>
                    </a>
                </div>
            </header>
            
            <main class="main-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo __('Total Clients'); ?></h3>
                        <div class="stat-value"><?php echo $stats['total_clients']; ?></div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> <?php echo __('Active organizations'); ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo __('Active Projects'); ?></h3>
                        <div class="stat-value"><?php echo $stats['active_projects']; ?></div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> <?php echo __('In progress'); ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo __('Open Risks'); ?></h3>
                        <div class="stat-value"><?php echo $stats['open_risks']; ?></div>
                        <div class="stat-change <?php echo $stats['open_risks'] > 0 ? 'negative' : 'positive'; ?>">
                            <i class="fas fa-<?php echo $stats['open_risks'] > 0 ? 'exclamation-triangle' : 'check'; ?>"></i> 
                            <?php echo $stats['open_risks'] > 0 ? __('Requires attention') : __('All clear'); ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo __('High Priority'); ?></h3>
                        <div class="stat-value"><?php echo $stats['high_priority_risks']; ?></div>
                        <div class="stat-change <?php echo $stats['high_priority_risks'] > 0 ? 'negative' : 'positive'; ?>">
                            <i class="fas fa-<?php echo $stats['high_priority_risks'] > 0 ? 'arrow-up' : 'check'; ?>"></i> 
                            <?php echo $stats['high_priority_risks'] > 0 ? __('Critical risks') : __('No critical risks'); ?>
                        </div>
                    </div>
                </div>

                <div class="charts-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo __('Risk Distribution by Criticality'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="riskDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo __('Risk Trend (Last 6 Months)'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="riskTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($recent_risks)): ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo __('Recent High Priority Risks'); ?></h3>
                        <a href="risks.php" class="btn btn-primary">
                            <i class="fas fa-eye"></i> <?php echo __('View All Risks'); ?>
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo __('Risk ID'); ?></th>
                                    <th><?php echo __('Risk Name'); ?></th>
                                    <th><?php echo __('Project'); ?></th>
                                    <th><?php echo __('Criticality'); ?></th>
                                    <th><?php echo __('Status'); ?></th>
                                    <th><?php echo __('Date Created'); ?></th>
                                    <th><?php echo __('Actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_risks as $risk): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($risk['code']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($risk['name']); ?></td>
                                    <td><?php echo htmlspecialchars($risk['project_name']); ?></td>
                                    <td>
                                        <?php 
                                        $criticality = $risk['brutCriticality'];
                                        $status_class = 'status-low';
                                        $status_text = __('Low');
                                        
                                        if ($criticality >= 20) {
                                            $status_class = 'status-critical';
                                            $status_text = __('Critical');
                                        } elseif ($criticality >= 15) {
                                            $status_class = 'status-high';
                                            $status_text = __('High');
                                        } elseif ($criticality >= 10) {
                                            $status_class = 'status-medium';
                                            $status_text = __('Medium');
                                        }
                                        ?>
                                        <span class="status-badge <?php echo $status_class; ?>">
                                            <?php echo $status_text; ?> (<?php echo $criticality; ?>)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-open"><?php echo __('Open'); ?></span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($risk['createdAt'])); ?></td>
                                    <td>
                                        <a href="risk_details.php?id=<?php echo $risk['id']; ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                            <i class="fas fa-eye"></i> <?php echo __('View'); ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo __('Recent High Priority Risks'); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="empty-state">
                            <i class="fas fa-check-circle"></i>
                            <h3><?php echo __('No High Priority Risks'); ?></h3>
                            <p><?php echo __('Your system is running smoothly with no critical risks detected.'); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            
            // Menu toggle for mobile
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 1024 && 
                    !sidebar.contains(e.target) && 
                    !menuToggle.contains(e.target) && 
                    sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            });

            // Initialize Charts
            initializeCharts();
        });

        function initializeCharts() {
            // Risk Distribution Chart
            const riskDistCtx = document.getElementById('riskDistributionChart').getContext('2d');
            new Chart(riskDistCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Critical', 'High', 'Medium', 'Low'],
                    datasets: [{
            data: [5, 8, 10, 0],
            backgroundColor: [
                '#ef4444',
                '#f59e0b',
                '#10b981',
                '#64748b'
            ],
            borderWidth: 0,
            cutout: '60%'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    font: {
                        size: 12,
                        weight: '600'
                    }
                }
            }
        }
    }
});

const riskDistCtx = document.getElementById('riskDistributionChart').getContext('2d');
new Chart(riskDistCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(riskByCategory),
        datasets: [{
            data: Object.values(riskByCategory),
            backgroundColor: [
                '#ef4444',
                '#f59e0b',
                '#10b981',
                '#64748b',
                '#2563eb',
                '#3b82f6'
            ],
            borderWidth: 0,
            cutout: '60%'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    font: {
                        size: 12,
                        weight: '600'
                    }
                }
            }
        }
    }
});

// Risk Trend Chart with dynamic data
const riskTrendCtx = document.getElementById('riskTrendChart').getContext('2d');
new Chart(riskTrendCtx, {
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

            // Risk Trend Chart
            const riskTrendCtx = document.getElementById('riskTrendChart').getContext('2d');
            new Chart(riskTrendCtx, {
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
        }
    </script>
</body>
</html>

