<?php
session_start();

// Sample report data for demonstration
$reports = [
    [
        'id' => 1,
        'name' => 'Monthly Risk Assessment Report',
        'type' => 'Risk Assessment',
        'description' => 'Comprehensive monthly analysis of all identified risks and their current status',
        'project_name' => 'Customer Portal Redesign',
        'generated_by' => 'Risk Manager',
        'generated_at' => '2025-06-01',
        'status' => 'Published',
        'file_size' => '2.4 MB',
        'download_count' => 15
    ],
    [
        'id' => 2,
        'name' => 'Control Effectiveness Review',
        'type' => 'Control Assessment',
        'description' => 'Quarterly review of control effectiveness and recommendations for improvement',
        'project_name' => 'Mobile Banking App',
        'generated_by' => 'Compliance Officer',
        'generated_at' => '2025-05-15',
        'status' => 'Published',
        'file_size' => '1.8 MB',
        'download_count' => 8
    ],
    [
        'id' => 3,
        'name' => 'Project Risk Dashboard',
        'type' => 'Dashboard',
        'description' => 'Real-time dashboard showing current risk status across all active projects',
        'project_name' => 'All Projects',
        'generated_by' => 'System',
        'generated_at' => '2025-06-13',
        'status' => 'Live',
        'file_size' => 'N/A',
        'download_count' => 45
    ],
    [
        'id' => 4,
        'name' => 'Compliance Audit Report',
        'type' => 'Compliance',
        'description' => 'Annual compliance audit findings and regulatory adherence assessment',
        'project_name' => 'ERP System Implementation',
        'generated_by' => 'External Auditor',
        'generated_at' => '2025-04-30',
        'status' => 'Published',
        'file_size' => '5.2 MB',
        'download_count' => 22
    ],
    [
        'id' => 5,
        'name' => 'Incident Response Summary',
        'type' => 'Incident Report',
        'description' => 'Summary of security incidents and response actions taken in Q2 2025',
        'project_name' => 'Security Audit System',
        'generated_by' => 'Security Team',
        'generated_at' => '2025-06-10',
        'status' => 'Draft',
        'file_size' => '1.1 MB',
        'download_count' => 3
    ],
    [
        'id' => 6,
        'name' => 'Budget vs Risk Analysis',
        'type' => 'Financial',
        'description' => 'Analysis of risk mitigation costs versus potential financial impact',
        'project_name' => 'E-commerce Platform',
        'generated_by' => 'Financial Analyst',
        'generated_at' => '2025-05-28',
        'status' => 'Published',
        'file_size' => '3.1 MB',
        'download_count' => 12
    ]
];

$total_reports = count($reports);
$published_reports = count(array_filter($reports, function($report) { return $report['status'] === 'Published'; }));
$draft_reports = count(array_filter($reports, function($report) { return $report['status'] === 'Draft'; }));
$total_downloads = array_sum(array_column($reports, 'download_count'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Management - RiskGuard Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        }

        .btn-success:hover {
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
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

        .stat-change {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 500;
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
            padding: 0;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 16px 20px;
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

        .status-published {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-draft {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-live {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
            border: 1px solid rgba(37, 99, 235, 0.2);
        }

        .type-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            background: rgba(100, 116, 139, 0.1);
            color: var(--gray);
        }

        .report-details {
            font-size: 13px;
            color: var(--gray);
            margin-top: 4px;
            line-height: 1.4;
        }

        /* Search and Filter */
        .search-filter {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            align-items: center;
        }

        .search-box {
            flex: 1;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            background: var(--white);
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .filter-select {
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            background: var(--white);
            color: var(--dark);
            cursor: pointer;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .action-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .action-card i {
            font-size: 24px;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .action-card h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
        }

        .action-card p {
            font-size: 14px;
            color: var(--gray);
            line-height: 1.4;
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
            .quick-actions {
                grid-template-columns: 1fr;
            }
            .main-content {
                padding: 20px;
            }
            .header {
                padding: 0 20px 0 80px;
            }
            .search-filter {
                flex-direction: column;
                align-items: stretch;
            }
            .table {
                font-size: 14px;
            }
            .table th,
            .table td {
                padding: 12px 16px;
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
                    <li><a href="controls.php"><i class="fas fa-shield-check"></i> Controls</a></li>
                    <li><a href="reports.php" class="active"><i class="fas fa-file-alt"></i> Reports</a></li>
                </ul>
            </nav>
            
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=2563eb&color=fff&rounded=true" alt="Admin User">
                <div>
                    <strong>Admin User</strong>
                    <small>System Administrator</small>
                </div>
            </div>
        </aside>
        
        <div class="content">
            <header class="header">
                <div class="header-left">
                    <h1>Reports Management</h1>
                </div>
                <div class="header-right">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">2</span>
                    </div>
                    <a href="logout.php" class="btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>
            
            <main class="main-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Reports</h3>
                        <div class="stat-value"><?php echo $total_reports; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-file-alt"></i> All reports
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3>Published</h3>
                        <div class="stat-value"><?php echo $published_reports; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-check-circle"></i> Available reports
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3>Draft Reports</h3>
                        <div class="stat-value"><?php echo $draft_reports; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-edit"></i> In progress
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Downloads</h3>
                        <div class="stat-value"><?php echo $total_downloads; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-download"></i> Report usage
                        </div>
                    </div>
                </div>

                <div class="quick-actions">
                    <a href="report_generate.php" class="action-card">
                        <i class="fas fa-plus-circle"></i>
                        <h4>Generate New Report</h4>
                        <p>Create a custom report with specific parameters and filters</p>
                    </a>
                    <a href="dashboard_live.php" class="action-card">
                        <i class="fas fa-chart-line"></i>
                        <h4>Live Dashboard</h4>
                        <p>View real-time risk metrics and key performance indicators</p>
                    </a>
                    <a href="report_schedule.php" class="action-card">
                        <i class="fas fa-clock"></i>
                        <h4>Schedule Reports</h4>
                        <p>Set up automated report generation and distribution</p>
                    </a>
                    <a href="report_templates.php" class="action-card">
                        <i class="fas fa-file-contract"></i>
                        <h4>Report Templates</h4>
                        <p>Manage and customize report templates for different needs</p>
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Report Library</h3>
                        <a href="report_generate.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> Generate Report
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="search-filter">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search reports..." id="searchInput">
                            </div>
                            <select class="filter-select" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="Risk Assessment">Risk Assessment</option>
                                <option value="Control Assessment">Control Assessment</option>
                                <option value="Compliance">Compliance</option>
                                <option value="Dashboard">Dashboard</option>
                                <option value="Incident Report">Incident Report</option>
                                <option value="Financial">Financial</option>
                            </select>
                            <select class="filter-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="Published">Published</option>
                                <option value="Draft">Draft</option>
                                <option value="Live">Live</option>
                            </select>
                            <select class="filter-select" id="projectFilter">
                                <option value="">All Projects</option>
                                <option value="Customer Portal Redesign">Customer Portal Redesign</option>
                                <option value="Mobile Banking App">Mobile Banking App</option>
                                <option value="ERP System Implementation">ERP System Implementation</option>
                                <option value="All Projects">All Projects</option>
                            </select>
                        </div>
                        
                        <table class="table" id="reportsTable">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Type</th>
                                    <th>Project</th>
                                    <th>Generated By</th>
                                    <th>Generated Date</th>
                                    <th>Status</th>
                                    <th>Size</th>
                                    <th>Downloads</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($report['name']); ?></strong>
                                            <div class="report-details">
                                                <?php echo htmlspecialchars(substr($report['description'], 0, 80)) . '...'; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="type-badge">
                                            <?php echo $report['type']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($report['project_name']); ?></td>
                                    <td><?php echo htmlspecialchars($report['generated_by']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($report['generated_at'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($report['status']); ?>">
                                            <?php echo $report['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $report['file_size']; ?></td>
                                    <td><?php echo $report['download_count']; ?></td>
                                    <td>
                                        <?php if ($report['status'] === 'Live'): ?>
                                            <a href="dashboard_view.php?id=<?php echo $report['id']; ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        <?php else: ?>
                                            <a href="report_download.php?id=<?php echo $report['id']; ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        <?php endif; ?>
                                        <a href="report_details.php?id=<?php echo $report['id']; ?>" class="btn btn-primary btn-sm" style="background: var(--warning);">
                                            <i class="fas fa-info-circle"></i> Details
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            const searchInput = document.getElementById('searchInput');
            const typeFilter = document.getElementById('typeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const projectFilter = document.getElementById('projectFilter');
            const table = document.getElementById('reportsTable');
            
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

            // Search and filter functionality
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const typeValue = typeFilter.value;
                const statusValue = statusFilter.value;
                const projectValue = projectFilter.value;
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const reportName = cells[0].textContent.toLowerCase();
                    const type = cells[1].textContent.trim();
                    const project = cells[2].textContent;
                    const status = cells[5].textContent.trim();

                    const matchesSearch = reportName.includes(searchTerm);
                    const matchesType = !typeValue || type === typeValue;
                    const matchesStatus = !statusValue || status === statusValue;
                    const matchesProject = !projectValue || project === projectValue;

                    if (matchesSearch && matchesType && matchesStatus && matchesProject) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterTable);
            typeFilter.addEventListener('change', filterTable);
            statusFilter.addEventListener('change', filterTable);
            projectFilter.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>

