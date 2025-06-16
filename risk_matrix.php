<?php
session_start();

// Sample risk data for matrix visualization
$risks = [
    [
        'id' => 1,
        'name' => 'Data Security Vulnerability',
        'code' => 'RISK-001',
        'frequency' => 3,
        'avgImpact' => 4.2,
        'brutCriticality' => 22,
        'project_name' => 'Customer Portal',
        'status' => 'Active'
    ],
    [
        'id' => 2,
        'name' => 'Budget Overrun Risk',
        'code' => 'RISK-002',
        'frequency' => 4,
        'avgImpact' => 3.8,
        'brutCriticality' => 18,
        'project_name' => 'Mobile Application',
        'status' => 'Active'
    ],
    [
        'id' => 3,
        'name' => 'Key Personnel Unavailability',
        'code' => 'RISK-003',
        'frequency' => 2,
        'avgImpact' => 3.4,
        'brutCriticality' => 15,
        'project_name' => 'Data Migration',
        'status' => 'Active'
    ],
    [
        'id' => 4,
        'name' => 'Third-party Integration Failure',
        'code' => 'RISK-004',
        'frequency' => 3,
        'avgImpact' => 3.2,
        'brutCriticality' => 16,
        'project_name' => 'E-commerce Platform',
        'status' => 'Active'
    ],
    [
        'id' => 5,
        'name' => 'Regulatory Compliance Gap',
        'code' => 'RISK-005',
        'frequency' => 2,
        'avgImpact' => 4.6,
        'brutCriticality' => 20,
        'project_name' => 'Financial System',
        'status' => 'Active'
    ],
    [
        'id' => 6,
        'name' => 'System Performance Degradation',
        'code' => 'RISK-006',
        'frequency' => 4,
        'avgImpact' => 2.8,
        'brutCriticality' => 12,
        'project_name' => 'Infrastructure Upgrade',
        'status' => 'Active'
    ],
    [
        'id' => 7,
        'name' => 'Vendor Dependency Risk',
        'code' => 'RISK-007',
        'frequency' => 2,
        'avgImpact' => 3.6,
        'brutCriticality' => 14,
        'project_name' => 'Cloud Migration',
        'status' => 'Active'
    ],
    [
        'id' => 8,
        'name' => 'Data Loss Risk',
        'code' => 'RISK-008',
        'frequency' => 1,
        'avgImpact' => 4.8,
        'brutCriticality' => 19,
        'project_name' => 'Backup System',
        'status' => 'Active'
    ]
];

$total_risks = count($risks);
$high_risks = count(array_filter($risks, function($risk) { return $risk['brutCriticality'] >= 18; }));
$medium_risks = count(array_filter($risks, function($risk) { return $risk['brutCriticality'] >= 12 && $risk['brutCriticality'] < 18; }));
$low_risks = count(array_filter($risks, function($risk) { return $risk['brutCriticality'] < 12; }));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Matrix - RiskGuard Pro</title>
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

        .btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
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
        }

        .stat-card.high::before {
            background: linear-gradient(90deg, var(--danger), #dc2626);
        }

        .stat-card.medium::before {
            background: linear-gradient(90deg, var(--warning), #d97706);
        }

        .stat-card.low::before {
            background: linear-gradient(90deg, var(--success), #059669);
        }

        .stat-card.total::before {
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
            font-size: 32px;
            font-weight: 800;
            margin: 8px 0 12px 0;
            color: var(--dark);
            line-height: 1;
        }

        /* Risk Matrix */
        .matrix-container {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .matrix-header {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, #fafbfc 0%, #f1f5f9 100%);
        }

        .matrix-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.3px;
        }

        .matrix-body {
            padding: 28px;
        }

        .risk-matrix {
            display: grid;
            grid-template-columns: 80px repeat(5, 1fr);
            grid-template-rows: 40px repeat(5, 80px);
            gap: 2px;
            background: var(--border);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .matrix-cell {
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            position: relative;
        }

        .matrix-header-cell {
            background: var(--light);
            font-size: 12px;
            color: var(--dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .matrix-label {
            background: var(--light);
            font-size: 12px;
            color: var(--dark);
            writing-mode: vertical-rl;
            text-orientation: mixed;
        }

        .risk-cell {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .risk-cell:hover {
            transform: scale(1.05);
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .risk-cell.high {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 2px solid var(--danger);
        }

        .risk-cell.medium {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border: 2px solid var(--warning);
        }

        .risk-cell.low {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 2px solid var(--success);
        }

        .risk-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 2px;
            position: relative;
            cursor: pointer;
        }

        .risk-dot.high {
            background: var(--danger);
        }

        .risk-dot.medium {
            background: var(--warning);
        }

        .risk-dot.low {
            background: var(--success);
        }

        .risk-tooltip {
            position: absolute;
            background: var(--dark);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            transform: translateX(-50%);
            bottom: 100%;
            left: 50%;
            margin-bottom: 5px;
        }

        .risk-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: var(--dark);
        }

        .risk-dot:hover .risk-tooltip {
            opacity: 1;
        }

        /* Legend */
        .matrix-legend {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 50%;
        }

        .legend-color.high {
            background: var(--danger);
        }

        .legend-color.medium {
            background: var(--warning);
        }

        .legend-color.low {
            background: var(--success);
        }

        /* Risk List */
        .risk-list {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .risk-list-header {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, #fafbfc 0%, #f1f5f9 100%);
        }

        .risk-list-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.3px;
        }

        .risk-item {
            padding: 16px 28px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: between;
            transition: all 0.2s ease;
        }

        .risk-item:hover {
            background: rgba(59, 130, 246, 0.04);
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

        .risk-details {
            font-size: 14px;
            color: var(--gray);
        }

        .risk-score {
            text-align: right;
            margin-left: 20px;
        }

        .score-value {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .score-value.high {
            color: var(--danger);
        }

        .score-value.medium {
            color: var(--warning);
        }

        .score-value.low {
            color: var(--success);
        }

        .score-label {
            font-size: 12px;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
                grid-template-columns: 1fr 1fr;
            }
            .main-content {
                padding: 20px;
            }
            .header {
                padding: 0 20px 0 80px;
            }
            .risk-matrix {
                grid-template-columns: 60px repeat(5, 1fr);
                grid-template-rows: 30px repeat(5, 60px);
            }
            .matrix-legend {
                flex-direction: column;
                align-items: center;
                gap: 12px;
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
                    <li><a href="risk_matrix.php" class="active"><i class="fas fa-th"></i> Risk Matrix</a></li>
                    <li><a href="controls.php"><i class="fas fa-shield-check"></i> Controls</a></li>
                    <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
                    <li><a href="analytics.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
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
                    <h1><i class="fas fa-th"></i> Risk Matrix</h1>
                </div>
                <div class="header-right">
                    <a href="add_risk.php" class="btn">
                        <i class="fas fa-plus"></i> Add Risk
                    </a>
                </div>
            </header>

            <div class="main-content">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card total">
                        <h3>Total Risks</h3>
                        <div class="stat-value"><?php echo $total_risks; ?></div>
                    </div>
                    <div class="stat-card high">
                        <h3>High Risk</h3>
                        <div class="stat-value"><?php echo $high_risks; ?></div>
                    </div>
                    <div class="stat-card medium">
                        <h3>Medium Risk</h3>
                        <div class="stat-value"><?php echo $medium_risks; ?></div>
                    </div>
                    <div class="stat-card low">
                        <h3>Low Risk</h3>
                        <div class="stat-value"><?php echo $low_risks; ?></div>
                    </div>
                </div>

                <!-- Risk Matrix -->
                <div class="matrix-container">
                    <div class="matrix-header">
                        <div class="matrix-title">Risk Probability vs Impact Matrix</div>
                    </div>
                    <div class="matrix-body">
                        <div class="risk-matrix" id="riskMatrix">
                            <!-- Matrix headers -->
                            <div class="matrix-cell"></div>
                            <div class="matrix-cell matrix-header-cell">Very Low</div>
                            <div class="matrix-cell matrix-header-cell">Low</div>
                            <div class="matrix-cell matrix-header-cell">Medium</div>
                            <div class="matrix-cell matrix-header-cell">High</div>
                            <div class="matrix-cell matrix-header-cell">Very High</div>
                            
                            <!-- Matrix rows -->
                            <div class="matrix-cell matrix-label">Very High</div>
                            <div class="matrix-cell risk-cell medium" data-prob="5" data-impact="1"></div>
                            <div class="matrix-cell risk-cell medium" data-prob="5" data-impact="2"></div>
                            <div class="matrix-cell risk-cell high" data-prob="5" data-impact="3"></div>
                            <div class="matrix-cell risk-cell high" data-prob="5" data-impact="4"></div>
                            <div class="matrix-cell risk-cell high" data-prob="5" data-impact="5"></div>
                            
                            <div class="matrix-cell matrix-label">High</div>
                            <div class="matrix-cell risk-cell low" data-prob="4" data-impact="1"></div>
                            <div class="matrix-cell risk-cell medium" data-prob="4" data-impact="2"></div>
                            <div class="matrix-cell risk-cell medium" data-prob="4" data-impact="3"></div>
                            <div class="matrix-cell risk-cell high" data-prob="4" data-impact="4"></div>
                            <div class="matrix-cell risk-cell high" data-prob="4" data-impact="5"></div>
                            
                            <div class="matrix-cell matrix-label">Medium</div>
                            <div class="matrix-cell risk-cell low" data-prob="3" data-impact="1"></div>
                            <div class="matrix-cell risk-cell low" data-prob="3" data-impact="2"></div>
                            <div class="matrix-cell risk-cell medium" data-prob="3" data-impact="3"></div>
                            <div class="matrix-cell risk-cell medium" data-prob="3" data-impact="4"></div>
                            <div class="matrix-cell risk-cell high" data-prob="3" data-impact="5"></div>
                            
                            <div class="matrix-cell matrix-label">Low</div>
                            <div class="matrix-cell risk-cell low" data-prob="2" data-impact="1"></div>
                            <div class="matrix-cell risk-cell low" data-prob="2" data-impact="2"></div>
                            <div class="matrix-cell risk-cell low" data-prob="2" data-impact="3"></div>
                            <div class="matrix-cell risk-cell medium" data-prob="2" data-impact="4"></div>
                            <div class="matrix-cell risk-cell medium" data-prob="2" data-impact="5"></div>
                            
                            <div class="matrix-cell matrix-label">Very Low</div>
                            <div class="matrix-cell risk-cell low" data-prob="1" data-impact="1"></div>
                            <div class="matrix-cell risk-cell low" data-prob="1" data-impact="2"></div>
                            <div class="matrix-cell risk-cell low" data-prob="1" data-impact="3"></div>
                            <div class="matrix-cell risk-cell low" data-prob="1" data-impact="4"></div>
                            <div class="matrix-cell risk-cell medium" data-prob="1" data-impact="5"></div>
                        </div>
                        
                        <div class="matrix-legend">
                            <div class="legend-item">
                                <div class="legend-color high"></div>
                                <span>High Risk (≥18)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color medium"></div>
                                <span>Medium Risk (12-17)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color low"></div>
                                <span>Low Risk (<12)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Risk List -->
                <div class="risk-list">
                    <div class="risk-list-header">
                        <div class="risk-list-title">Risk Details</div>
                    </div>
                    <?php foreach ($risks as $risk): ?>
                        <div class="risk-item">
                            <div class="risk-info">
                                <div class="risk-name"><?php echo htmlspecialchars($risk['name']); ?></div>
                                <div class="risk-details">
                                    <?php echo htmlspecialchars($risk['code']); ?> • 
                                    <?php echo htmlspecialchars($risk['project_name']); ?> • 
                                    Probability: <?php echo $risk['frequency']; ?>/5 • 
                                    Impact: <?php echo number_format($risk['avgImpact'], 1); ?>/5
                                </div>
                            </div>
                            <div class="risk-score">
                                <div class="score-value <?php 
                                    if ($risk['brutCriticality'] >= 18) echo 'high';
                                    elseif ($risk['brutCriticality'] >= 12) echo 'medium';
                                    else echo 'low';
                                ?>">
                                    <?php echo $risk['brutCriticality']; ?>
                                </div>
                                <div class="score-label">Risk Score</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Risk data from PHP
        const risks = <?php echo json_encode($risks); ?>;
        
        // Populate risk matrix with dots
        function populateMatrix() {
            risks.forEach(risk => {
                const probability = risk.frequency;
                const impact = Math.round(risk.avgImpact);
                
                const cell = document.querySelector(`[data-prob="${probability}"][data-impact="${impact}"]`);
                if (cell) {
                    const dot = document.createElement('div');
                    dot.className = 'risk-dot';
                    
                    if (risk.brutCriticality >= 18) {
                        dot.classList.add('high');
                    } else if (risk.brutCriticality >= 12) {
                        dot.classList.add('medium');
                    } else {
                        dot.classList.add('low');
                    }
                    
                    const tooltip = document.createElement('div');
                    tooltip.className = 'risk-tooltip';
                    tooltip.textContent = `${risk.name} (${risk.code})`;
                    dot.appendChild(tooltip);
                    
                    dot.addEventListener('click', () => {
                        window.location.href = `risk.php?id=${risk.id}`;
                    });
                    
                    cell.appendChild(dot);
                }
            });
        }

        // Mobile menu toggle
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Initialize matrix
        populateMatrix();
    </script>
</body>
</html>

