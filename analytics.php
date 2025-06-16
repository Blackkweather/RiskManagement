<?php
session_start();

// Sample analytics data for comprehensive dashboard
$analytics_data = [
    'risk_trends' => [
        'January' => ['high' => 5, 'medium' => 12, 'low' => 8],
        'February' => ['high' => 7, 'medium' => 15, 'low' => 6],
        'March' => ['high' => 4, 'medium' => 18, 'low' => 9],
        'April' => ['high' => 8, 'medium' => 14, 'low' => 7],
        'May' => ['high' => 6, 'medium' => 16, 'low' => 11],
        'June' => ['high' => 9, 'medium' => 13, 'low' => 5]
    ],
    'risk_by_category' => [
        'Operational' => 15,
        'Financial' => 8,
        'Strategic' => 6,
        'Compliance' => 12,
        'Technology' => 10,
        'Reputational' => 4
    ],
    'mitigation_effectiveness' => [
        'Fully Mitigated' => 18,
        'Partially Mitigated' => 22,
        'Under Review' => 8,
        'Not Mitigated' => 7
    ],
    'project_risk_distribution' => [
        'Customer Portal' => ['risks' => 12, 'high_priority' => 3],
        'Mobile Banking' => ['risks' => 8, 'high_priority' => 2],
        'ERP Implementation' => ['risks' => 15, 'high_priority' => 4],
        'E-commerce Platform' => ['risks' => 10, 'high_priority' => 2],
        'Cloud Migration' => ['risks' => 6, 'high_priority' => 1]
    ],
    'control_effectiveness' => [
        'Preventive' => 85,
        'Detective' => 78,
        'Corrective' => 92,
        'Compensating' => 67
    ],
    'risk_velocity' => [
        'Week 1' => 3,
        'Week 2' => 5,
        'Week 3' => 2,
        'Week 4' => 7,
        'Week 5' => 4,
        'Week 6' => 6
    ]
];

// Calculate key metrics
$total_risks = array_sum(array_column($analytics_data['project_risk_distribution'], 'risks'));
$high_priority_risks = array_sum(array_column($analytics_data['project_risk_distribution'], 'high_priority'));
$risk_coverage = round(($analytics_data['mitigation_effectiveness']['Fully Mitigated'] + $analytics_data['mitigation_effectiveness']['Partially Mitigated']) / array_sum($analytics_data['mitigation_effectiveness']) * 100, 1);
$avg_control_effectiveness = round(array_sum($analytics_data['control_effectiveness']) / count($analytics_data['control_effectiveness']), 1);

// Recent activities for timeline
$recent_activities = [
    [
        'type' => 'risk_created',
        'title' => 'New Risk Identified',
        'description' => 'Data Security Vulnerability in Customer Portal',
        'timestamp' => '2025-06-15 14:30',
        'severity' => 'high',
        'user' => 'John Doe'
    ],
    [
        'type' => 'control_updated',
        'title' => 'Control Effectiveness Updated',
        'description' => 'Authentication controls effectiveness increased to 95%',
        'timestamp' => '2025-06-15 11:15',
        'severity' => 'medium',
        'user' => 'Jane Smith'
    ],
    [
        'type' => 'report_generated',
        'title' => 'Monthly Report Generated',
        'description' => 'Risk Assessment Report for May 2025 completed',
        'timestamp' => '2025-06-14 16:45',
        'severity' => 'low',
        'user' => 'System'
    ],
    [
        'type' => 'risk_mitigated',
        'title' => 'Risk Successfully Mitigated',
        'description' => 'Budget overrun risk in Mobile Banking project resolved',
        'timestamp' => '2025-06-14 09:20',
        'severity' => 'medium',
        'user' => 'Mike Johnson'
    ],
    [
        'type' => 'audit_completed',
        'title' => 'Compliance Audit Completed',
        'description' => 'Q2 2025 compliance audit finished with 98% score',
        'timestamp' => '2025-06-13 17:00',
        'severity' => 'low',
        'user' => 'External Auditor'
    ]
];

// Top risks by criticality
$top_risks = [
    ['name' => 'Data Security Vulnerability', 'score' => 22, 'trend' => 'up'],
    ['name' => 'Regulatory Compliance Gap', 'score' => 20, 'trend' => 'stable'],
    ['name' => 'Budget Overrun Risk', 'score' => 18, 'trend' => 'down'],
    ['name' => 'Third-party Integration Failure', 'score' => 16, 'trend' => 'up'],
    ['name' => 'Key Personnel Unavailability', 'score' => 15, 'trend' => 'stable']
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
                    <h1><i class="fas fa-chart-bar"></i> Analytics Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="time-filter">
                        <button class="active" data-period="7d">7D</button>
                        <button data-period="30d">30D</button>
                        <button data-period="90d">90D</button>
                        <button data-period="1y">1Y</button>
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
                            <div class="kpi-title">Total Risks</div>
                            <div class="kpi-icon primary">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="kpi-value"><?php echo $total_risks; ?></div>
                        <div class="kpi-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+12% from last month</span>
                        </div>
                    </div>
                    
                    <div class="kpi-card danger">
                        <div class="kpi-header">
                            <div class="kpi-title">High Priority</div>
                            <div class="kpi-icon danger">
                                <i class="fas fa-fire"></i>
                            </div>
                        </div>
                        <div class="kpi-value"><?php echo $high_priority_risks; ?></div>
                        <div class="kpi-change negative">
                            <i class="fas fa-arrow-down"></i>
                            <span>-8% from last month</span>
                        </div>
                    </div>
                    
                    <div class="kpi-card success">
                        <div class="kpi-header">
                            <div class="kpi-title">Risk Coverage</div>
                            <div class="kpi-icon success">
                                <i class="fas fa-shield-check"></i>
                            </div>
                        </div>
                        <div class="kpi-value"><?php echo $risk_coverage; ?>%</div>
                        <div class="kpi-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+5% from last month</span>
                        </div>
                    </div>
                    
                    <div class="kpi-card warning">
                        <div class="kpi-header">
                            <div class="kpi-title">Control Effectiveness</div>
                            <div class="kpi-icon warning">
                                <i class="fas fa-cogs"></i>
                            </div>
                        </div>
                        <div class="kpi-value"><?php echo $avg_control_effectiveness; ?>%</div>
                        <div class="kpi-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+3% from last month</span>
                        </div>
                    </div>
                </div>

                <!-- Main Charts -->
                <div class="charts-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Risk Trends Over Time</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="riskTrendsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Recent Activities</div>
                        </div>
                        <div class="card-body">
                            <div class="activity-timeline">
                                <?php foreach ($recent_activities as $activity): ?>
                                <div class="activity-item">
                                    <div class="activity-icon <?php echo $activity['severity']; ?>">
                                        <i class="fas fa-<?php 
                                            switch($activity['type']) {
                                                case 'risk_created': echo 'plus';
                                                case 'control_updated': echo 'edit';
                                                case 'report_generated': echo 'file-alt';
                                                case 'risk_mitigated': echo 'check';
                                                case 'audit_completed': echo 'clipboard-check';
                                                default: echo 'info';
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
                            <div class="card-title">Risk Distribution by Category</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container small">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Mitigation Status</div>
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
                            <div class="card-title">Top Risks by Criticality</div>
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
                            <div class="card-title">Control Effectiveness by Type</div>
                        </div>
                        <div class="card-body">
                            <div class="progress-list">
                                <?php foreach ($analytics_data['control_effectiveness'] as $type => $effectiveness): ?>
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
        const months = Object.keys(riskTrends);
        const highRisks = months.map(month => riskTrends[month].high);
        const mediumRisks = months.map(month => riskTrends[month].medium);
        const lowRisks = months.map(month => riskTrends[month].low);

        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'High Risk',
                        data: highRisks,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Medium Risk',
                        data: mediumRisks,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Low Risk',
                        data: lowRisks,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
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
                            color: '#e2e8f0'
                        }
                    }
                }
            }
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(riskByCategory),
                datasets: [{
                    data: Object.values(riskByCategory),
                    backgroundColor: [
                        '#3b82f6',
                        '#ef4444',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6',
                        '#06b6d4'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Mitigation Chart
        const mitigationCtx = document.getElementById('mitigationChart').getContext('2d');
        new Chart(mitigationCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(mitigationEffectiveness),
                datasets: [{
                    data: Object.values(mitigationEffectiveness),
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#3b82f6',
                        '#ef4444'
                    ],
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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

