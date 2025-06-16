<?php
session_start();

// Enhanced report data with more comprehensive information
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
        'download_count' => 15,
        'format' => 'PDF',
        'category' => 'Operational'
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
        'download_count' => 8,
        'format' => 'PDF',
        'category' => 'Compliance'
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
        'download_count' => 45,
        'format' => 'Interactive',
        'category' => 'Executive'
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
        'download_count' => 22,
        'format' => 'PDF',
        'category' => 'Regulatory'
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
        'download_count' => 3,
        'format' => 'PDF',
        'category' => 'Security'
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
        'download_count' => 12,
        'format' => 'Excel',
        'category' => 'Financial'
    ],
    [
        'id' => 7,
        'name' => 'Risk Trend Analysis',
        'type' => 'Analytics',
        'description' => 'Quarterly analysis of risk trends and patterns across all business units',
        'project_name' => 'All Projects',
        'generated_by' => 'Data Analyst',
        'generated_at' => '2025-06-05',
        'status' => 'Published',
        'file_size' => '4.2 MB',
        'download_count' => 18,
        'format' => 'PDF',
        'category' => 'Strategic'
    ],
    [
        'id' => 8,
        'name' => 'Executive Risk Summary',
        'type' => 'Executive Summary',
        'description' => 'High-level overview of key risks and mitigation strategies for executive review',
        'project_name' => 'All Projects',
        'generated_by' => 'Chief Risk Officer',
        'generated_at' => '2025-06-12',
        'status' => 'Published',
        'file_size' => '0.8 MB',
        'download_count' => 35,
        'format' => 'PDF',
        'category' => 'Executive'
    ]
];

// Calculate statistics
$total_reports = count($reports);
$published_reports = count(array_filter($reports, function($report) { return $report['status'] === 'Published'; }));
$draft_reports = count(array_filter($reports, function($report) { return $report['status'] === 'Draft'; }));
$total_downloads = array_sum(array_column($reports, 'download_count'));

// Group reports by category for better organization
$categories = [];
foreach ($reports as $report) {
    $categories[$report['category']][] = $report;
}

// Sample chart data for analytics
$monthly_downloads = [
    'January' => 45,
    'February' => 52,
    'March' => 38,
    'April' => 67,
    'May' => 73,
    'June' => 89
];

$report_types = [
    'Risk Assessment' => 3,
    'Compliance' => 2,
    'Financial' => 1,
    'Security' => 1,
    'Executive' => 1
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Management - RiskGuard Pro</title>
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

        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
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

        .chart-container {
            height: 300px;
            position: relative;
        }

        /* Search and Filter */
        .search-filter {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
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
            min-width: 150px;
        }

        /* Report Categories */
        .category-section {
            margin-bottom: 32px;
        }

        .category-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 16px 0;
            border-bottom: 2px solid var(--border);
        }

        .category-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
        }

        .category-count {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .report-card {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .report-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
        }

        .report-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .report-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 12px;
            color: var(--gray);
            margin-bottom: 12px;
        }

        .report-description {
            font-size: 14px;
            color: var(--gray);
            line-height: 1.5;
        }

        .report-body {
            padding: 20px;
        }

        .report-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .report-stat {
            text-align: center;
        }

        .report-stat-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
        }

        .report-stat-label {
            font-size: 11px;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-actions {
            display: flex;
            gap: 8px;
        }

        .report-actions .btn {
            flex: 1;
            justify-content: center;
            padding: 8px 12px;
            font-size: 12px;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
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

        .format-badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
            background: var(--light);
            color: var(--gray);
        }

        /* Report Builder Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--white);
            border-radius: 16px;
            padding: 32px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            margin-bottom: 24px;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-secondary {
            background: var(--light);
            color: var(--dark);
        }

        .btn-secondary:hover {
            background: var(--border);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
            .search-filter {
                flex-direction: column;
                align-items: stretch;
            }
            .reports-grid {
                grid-template-columns: 1fr;
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
                    <li><a href="reports.php" class="active"><i class="fas fa-file-alt"></i> Reports</a></li>
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
                    <h1><i class="fas fa-file-alt"></i> Reports Management</h1>
                </div>
                <div class="header-right">
                    <button class="btn btn-success" onclick="openReportBuilder()">
                        <i class="fas fa-plus"></i> Generate Report
                    </button>
                    <a href="#" class="btn">
                        <i class="fas fa-download"></i> Export All
                    </a>
                </div>
            </header>

            <div class="main-content">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Reports</h3>
                        <div class="stat-value"><?php echo $total_reports; ?></div>
                        <div class="stat-change">All time</div>
                    </div>
                    <div class="stat-card">
                        <h3>Published</h3>
                        <div class="stat-value"><?php echo $published_reports; ?></div>
                        <div class="stat-change">Ready for download</div>
                    </div>
                    <div class="stat-card">
                        <h3>Draft Reports</h3>
                        <div class="stat-value"><?php echo $draft_reports; ?></div>
                        <div class="stat-change">In progress</div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Downloads</h3>
                        <div class="stat-value"><?php echo $total_downloads; ?></div>
                        <div class="stat-change">This month</div>
                    </div>
                </div>

                <!-- Analytics Charts -->
                <div class="charts-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Monthly Download Trends</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="downloadsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Report Types Distribution</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="typesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="search-filter">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search reports..." id="searchInput">
                    </div>
                    <select class="filter-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="Published">Published</option>
                        <option value="Draft">Draft</option>
                        <option value="Live">Live</option>
                    </select>
                    <select class="filter-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="Executive">Executive</option>
                        <option value="Operational">Operational</option>
                        <option value="Compliance">Compliance</option>
                        <option value="Financial">Financial</option>
                        <option value="Security">Security</option>
                        <option value="Strategic">Strategic</option>
                        <option value="Regulatory">Regulatory</option>
                    </select>
                    <select class="filter-select" id="formatFilter">
                        <option value="">All Formats</option>
                        <option value="PDF">PDF</option>
                        <option value="Excel">Excel</option>
                        <option value="Interactive">Interactive</option>
                    </select>
                </div>

                <!-- Reports by Category -->
                <?php foreach ($categories as $category => $categoryReports): ?>
                <div class="category-section">
                    <div class="category-header">
                        <div class="category-title"><?php echo htmlspecialchars($category); ?> Reports</div>
                        <div class="category-count"><?php echo count($categoryReports); ?></div>
                    </div>
                    <div class="reports-grid">
                        <?php foreach ($categoryReports as $report): ?>
                        <div class="report-card" data-status="<?php echo $report['status']; ?>" data-category="<?php echo $report['category']; ?>" data-format="<?php echo $report['format']; ?>">
                            <div class="report-header">
                                <div class="report-title"><?php echo htmlspecialchars($report['name']); ?></div>
                                <div class="report-meta">
                                    <span class="status-badge status-<?php echo strtolower($report['status']); ?>">
                                        <?php echo $report['status']; ?>
                                    </span>
                                    <span class="format-badge"><?php echo $report['format']; ?></span>
                                    <span><?php echo date('M j, Y', strtotime($report['generated_at'])); ?></span>
                                </div>
                                <div class="report-description">
                                    <?php echo htmlspecialchars($report['description']); ?>
                                </div>
                            </div>
                            <div class="report-body">
                                <div class="report-stats">
                                    <div class="report-stat">
                                        <div class="report-stat-value"><?php echo $report['download_count']; ?></div>
                                        <div class="report-stat-label">Downloads</div>
                                    </div>
                                    <div class="report-stat">
                                        <div class="report-stat-value"><?php echo $report['file_size']; ?></div>
                                        <div class="report-stat-label">Size</div>
                                    </div>
                                    <div class="report-stat">
                                        <div class="report-stat-value"><?php echo htmlspecialchars($report['generated_by']); ?></div>
                                        <div class="report-stat-label">Author</div>
                                    </div>
                                </div>
                                <div class="report-actions">
                                    <?php if ($report['status'] === 'Published' || $report['status'] === 'Live'): ?>
                                    <a href="#" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <?php endif; ?>
                                    <a href="#" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="#" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-share"></i> Share
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- Report Builder Modal -->
    <div class="modal" id="reportBuilderModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Generate New Report</div>
                <p>Create a custom report with your preferred settings and data sources.</p>
            </div>
            <form id="reportBuilderForm">
                <div class="form-group">
                    <label for="reportName">Report Name</label>
                    <input type="text" id="reportName" name="reportName" required placeholder="Enter report name">
                </div>
                <div class="form-group">
                    <label for="reportType">Report Type</label>
                    <select id="reportType" name="reportType" required>
                        <option value="">Select report type</option>
                        <option value="Risk Assessment">Risk Assessment</option>
                        <option value="Control Assessment">Control Assessment</option>
                        <option value="Compliance">Compliance Report</option>
                        <option value="Financial">Financial Analysis</option>
                        <option value="Executive Summary">Executive Summary</option>
                        <option value="Incident Report">Incident Report</option>
                        <option value="Analytics">Analytics Report</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reportProject">Project Scope</label>
                    <select id="reportProject" name="reportProject">
                        <option value="all">All Projects</option>
                        <option value="1">Customer Portal Redesign</option>
                        <option value="2">Mobile Banking App</option>
                        <option value="3">ERP System Implementation</option>
                        <option value="4">E-commerce Platform</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reportFormat">Output Format</label>
                    <select id="reportFormat" name="reportFormat" required>
                        <option value="PDF">PDF Document</option>
                        <option value="Excel">Excel Spreadsheet</option>
                        <option value="Interactive">Interactive Dashboard</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reportDescription">Description</label>
                    <textarea id="reportDescription" name="reportDescription" rows="3" placeholder="Describe the purpose and scope of this report"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeReportBuilder()">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-cog"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Chart data from PHP
        const monthlyDownloads = <?php echo json_encode($monthly_downloads); ?>;
        const reportTypes = <?php echo json_encode($report_types); ?>;

        // Downloads trend chart
        const downloadsCtx = document.getElementById('downloadsChart').getContext('2d');
        new Chart(downloadsCtx, {
            type: 'line',
            data: {
                labels: Object.keys(monthlyDownloads),
                datasets: [{
                    label: 'Downloads',
                    data: Object.values(monthlyDownloads),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
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
                            color: '#e2e8f0'
                        }
                    }
                }
            }
        });

        // Report types chart
        const typesCtx = document.getElementById('typesChart').getContext('2d');
        new Chart(typesCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(reportTypes),
                datasets: [{
                    data: Object.values(reportTypes),
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
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

        // Search and filter functionality
        function filterReports() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const categoryFilter = document.getElementById('categoryFilter').value;
            const formatFilter = document.getElementById('formatFilter').value;
            
            const reportCards = document.querySelectorAll('.report-card');
            
            reportCards.forEach(card => {
                const title = card.querySelector('.report-title').textContent.toLowerCase();
                const description = card.querySelector('.report-description').textContent.toLowerCase();
                const status = card.dataset.status;
                const category = card.dataset.category;
                const format = card.dataset.format;
                
                const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesCategory = !categoryFilter || category === categoryFilter;
                const matchesFormat = !formatFilter || format === formatFilter;
                
                if (matchesSearch && matchesStatus && matchesCategory && matchesFormat) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Event listeners for filters
        document.getElementById('searchInput').addEventListener('input', filterReports);
        document.getElementById('statusFilter').addEventListener('change', filterReports);
        document.getElementById('categoryFilter').addEventListener('change', filterReports);
        document.getElementById('formatFilter').addEventListener('change', filterReports);

        // Report builder modal functions
        function openReportBuilder() {
            document.getElementById('reportBuilderModal').classList.add('active');
        }

        function closeReportBuilder() {
            document.getElementById('reportBuilderModal').classList.remove('active');
        }

        // Report builder form submission
        document.getElementById('reportBuilderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simulate report generation
            alert('Report generation started! You will be notified when it\'s ready.');
            closeReportBuilder();
            
            // Reset form
            this.reset();
        });

        // Mobile menu toggle
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Close modal when clicking outside
        document.getElementById('reportBuilderModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReportBuilder();
            }
        });
    </script>
</body>
</html>

