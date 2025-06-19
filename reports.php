<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'lang/translation.php';
require_once 'config/database.php';

// Initialize variables
$total_reports = 0;
$published_reports = 0;
$draft_reports = 0;
$total_downloads = 0;
$categories = [];
$monthly_downloads = [];
$report_types = [];

try {
    // Connect to database
    $db = new Database();
    $conn = $db->getConnection();

    // Get all reports with their download counts
    $reportsQuery = "SELECT r.*, COUNT(rd.id) as actual_downloads,
                            e.name as entityName, p.name as projectName
                     FROM Report r 
                     LEFT JOIN ReportDownload rd ON r.id = rd.reportId
                     LEFT JOIN Entity e ON r.entityId = e.id
                     LEFT JOIN Project p ON r.projectId = p.id
                     GROUP BY r.id
                     ORDER BY r.createdAt DESC";
    
    $reports = [];
    if ($stmt = $conn->query($reportsQuery)) {
        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate statistics
        $total_reports = count($reports);
        $published_reports = count(array_filter($reports, function($r) { 
            return $r['status'] === 'Published'; 
        }));
        $draft_reports = $total_reports - $published_reports;
        
        // Group reports by type
        foreach ($reports as $report) {
            $type = $report['type'];
            if (!isset($categories[$type])) {
                $categories[$type] = [];
            }
            $categories[$type][] = $report;
            
            // Count downloads
            $total_downloads += intval($report['actual_downloads']);
        }
    }

    // Get monthly downloads for current year
    $monthly_downloads = array_fill(0, 12, 0); // Initialize all months with 0
    $current_year = date('Y');
    $downloadsQuery = "SELECT MONTH(downloadedAt) as month, COUNT(*) as count 
                      FROM ReportDownload 
                      WHERE YEAR(downloadedAt) = ?
                      GROUP BY MONTH(downloadedAt)";
    
    if ($stmt = $conn->prepare($downloadsQuery)) {
        $stmt->execute([$current_year]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $monthly_downloads[$row['month'] - 1] = intval($row['count']);
        }
    }

    // Get report types distribution
    $typesQuery = "SELECT type, COUNT(*) as count FROM Report GROUP BY type";
    if ($stmt = $conn->query($typesQuery)) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $report_types[$row['type']] = intval($row['count']);
        }
    }

} catch (PDOException $e) {
    // Log the error
    error_log("Database Error: " . $e->getMessage());
    $error_message = "Une erreur est survenue lors de la récupération des données.";
}

// If categories are empty, initialize with default types
if (empty($categories)) {
    $categories = [
        'Risk Assessment' => [],
        'Compliance' => [],
        'Financial' => [],
        'Security' => [],
        'Executive' => []
    ];
}

// Convert monthly_downloads to the format expected by the chart
$months = [
    'January', 'February', 'March', 'April', 
    'May', 'June', 'July', 'August',
    'September', 'October', 'November', 'December'
];
$monthly_downloads_chart = array_combine($months, $monthly_downloads);

// If report_types is empty, initialize with default types
if (empty($report_types)) {
    $report_types = [
        'Risk Assessment' => 0,
        'Compliance' => 0,
        'Financial' => 0,
        'Security' => 0,
        'Executive' => 0
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('Reports Management'); ?> - <?php echo __('RiskGuard Pro'); ?></title>
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
                <span><?php echo __('RiskGuard Pro'); ?></span>
            </div>
            
            <nav>
                <ul>
                    <li><a href="index.php"><i class="fas fa-chart-line"></i> <?php echo __('Dashboard'); ?></a></li>
                    <li><a href="clients.php"><i class="fas fa-building"></i> <?php echo __('Clients'); ?></a></li>
                    <li><a href="projects.php"><i class="fas fa-project-diagram"></i> <?php echo __('Projects'); ?></a></li>
                    <li><a href="entities.php"><i class="fas fa-sitemap"></i> <?php echo __('Entities'); ?></a></li>
                    <li><a href="processes.php"><i class="fas fa-cogs"></i> <?php echo __('Processes'); ?></a></li>
                    <li><a href="risks.php"><i class="fas fa-exclamation-triangle"></i> <?php echo __('Risks'); ?></a></li>
                    <li>
                        <a href="controls.php"><i class="fas fa-shield-check"></i> <?php echo __('Controls'); ?></a>
                    </li>
                    <li class="submenu">
                        <a href="reports.php" class="active"><i class="fas fa-file-alt"></i> <?php echo __('Reports'); ?> <i class="fas fa-chevron-down submenu-toggle"></i></a>
                        <ul>
                            <li><a href="risk_matrix.php"><?php echo __('Risk Matrix'); ?></a></li>
                            <li><a href="analytics.php"><?php echo __('Analytics'); ?></a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            
            <div class="user-info">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face" alt="<?php echo __('Admin User'); ?>">
                <div>
                    <strong><?php echo __('Admin User'); ?></strong>
                    <small><?php echo __('System Administrator'); ?></small>
                </div>
            </div>
        </aside>

        <main>
            <header class="header">
                <div class="header-left">
                    <h1><i class="fas fa-file-alt"></i> <?php echo __('Reports Management'); ?></h1>
                </div>
                <div class="header-right">
                    <button class="btn btn-success" onclick="openReportBuilder()">
                        <i class="fas fa-plus"></i> <?php echo __('Generate Report'); ?>
                    </button>
                    <a href="#" class="btn">
                        <i class="fas fa-download"></i> <?php echo __('Export All'); ?>
                    </a>
                </div>
            </header>

            <div class="main-content">
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php else: ?>
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo __('Total Reports'); ?></h3>
                        <div class="stat-value"><?php echo $total_reports; ?></div>
                        <div class="stat-change"><?php echo __('All time'); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo __('Published'); ?></h3>
                        <div class="stat-value"><?php echo $published_reports; ?></div>
                        <div class="stat-change"><?php echo __('Ready for download'); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo __('Draft Reports'); ?></h3>
                        <div class="stat-value"><?php echo $draft_reports; ?></div>
                        <div class="stat-change"><?php echo __('In progress'); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo __('Total Downloads'); ?></h3>
                        <div class="stat-value"><?php echo $total_downloads; ?></div>
                        <div class="stat-change"><?php echo __('All time'); ?></div>
                    </div>
                </div>

                <!-- Analytics Charts -->
                <div class="charts-grid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title"><?php echo __('Monthly Download Trends'); ?></div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="downloadsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title"><?php echo __('Report Types Distribution'); ?></div>
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
                        <input type="text" placeholder="<?php echo __('Search reports...'); ?>" id="searchInput">
                    </div>
                    <select class="filter-select" id="statusFilter">
                        <option value=""><?php echo __('All Status'); ?></option>
                        <option value="Published"><?php echo __('Published'); ?></option>
                        <option value="Draft"><?php echo __('Draft'); ?></option>
                    </select>
                    <select class="filter-select" id="categoryFilter">
                        <option value=""><?php echo __('All Categories'); ?></option>
                        <?php foreach (array_keys($categories) as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>">
                                <?php echo htmlspecialchars($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select class="filter-select" id="formatFilter">
                        <option value=""><?php echo __('All Formats'); ?></option>
                        <option value="PDF"><?php echo __('PDF'); ?></option>
                        <option value="Excel"><?php echo __('Excel'); ?></option>
                        <option value="Interactive"><?php echo __('Interactive'); ?></option>
                    </select>
                </div>

                <!-- Reports by Category -->
                <?php foreach ($categories as $type => $typeReports): ?>
                    <?php if (!empty($typeReports)): ?>
                    <div class="category-section">
                        <div class="category-header">
                            <div class="category-title"><?php echo htmlspecialchars($type); ?> <?php echo __('Reports'); ?></div>
                            <div class="category-count"><?php echo count($typeReports); ?></div>
                        </div>
                        <div class="reports-grid">
                            <?php foreach ($typeReports as $report): ?>
                            <div class="report-card" 
                                 data-status="<?php echo htmlspecialchars($report['status']); ?>" 
                                 data-category="<?php echo htmlspecialchars($report['type']); ?>" 
                                 data-format="<?php echo htmlspecialchars($report['format'] ?? 'PDF'); ?>">
                                <div class="report-header">
                                    <div class="report-title"><?php echo htmlspecialchars($report['name']); ?></div>
                                    <div class="report-meta">
                                        <span class="status-badge status-<?php echo strtolower($report['status']); ?>">
                                            <?php echo htmlspecialchars($report['status']); ?>
                                        </span>
                                        <span class="format-badge"><?php echo htmlspecialchars($report['format'] ?? 'PDF'); ?></span>
                                        <span><?php echo date('M j, Y', strtotime($report['createdAt'])); ?></span>
                                    </div>
                                    <div class="report-description">
                                        <?php echo htmlspecialchars($report['description'] ?? ''); ?>
                                    </div>
                                    <?php if (!empty($report['entityName'])): ?>
                                    <div class="report-meta">
                                        <span><i class="fas fa-building"></i> <?php echo htmlspecialchars($report['entityName']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="report-body">
                                    <div class="report-stats">
                                        <div class="report-stat">
                                            <div class="report-stat-value"><?php echo intval($report['actual_downloads'] ?? 0); ?></div>
                                            <div class="report-stat-label"><?php echo __('Downloads'); ?></div>
                                        </div>
                                        <div class="report-stat">
                                            <div class="report-stat-value"><?php echo htmlspecialchars($report['file_size'] ?? 'N/A'); ?></div>
                                            <div class="report-stat-label"><?php echo __('Size'); ?></div>
                                        </div>
                                        <div class="report-stat">
                                            <div class="report-stat-value"><?php echo htmlspecialchars($report['generated_by'] ?? 'System'); ?></div>
                                            <div class="report-stat-label"><?php echo __('Author'); ?></div>
                                        </div>
                                    </div>                                <div class="report-actions">
                                    <?php if ($report['status'] === 'Published'): ?>
<button class="btn btn-primary btn-sm download-btn" data-report-id="<?php echo $report['id']; ?>" data-format="pdf">
    <i class="fas fa-download"></i> <?php echo __('Download'); ?>
</button>
                                    <?php endif; ?>
                                    <a href="view_report.php?id=<?php echo $report['id']; ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-eye"></i> <?php echo __('View'); ?>
                                    </a>
                                    <a href="share_report.php?id=<?php echo $report['id']; ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-share"></i> <?php echo __('Share'); ?>
                                    </a>
                                </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Report Builder Modal -->
    <div class="modal" id="reportBuilderModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?php echo __('Generate New Report'); ?></div>
                <p><?php echo __('Create a custom report with your preferred settings and data sources.'); ?></p>
            </div>
            <form id="reportBuilderForm">
                <div class="form-group">
                    <label for="reportName"><?php echo __('Report Name'); ?></label>
                    <input type="text" id="reportName" name="reportName" required>
                </div>
                <div class="form-group">
                    <label for="reportType"><?php echo __('Report Type'); ?></label>
                    <select id="reportType" name="reportType" required>
                        <option value=""><?php echo __('Select report type'); ?></option>
                        <?php foreach (array_keys($categories) as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>">
                                <?php echo htmlspecialchars($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reportFormat"><?php echo __('Output Format'); ?></label>
                    <select id="reportFormat" name="reportFormat" required>
                        <option value="PDF"><?php echo __('PDF Document'); ?></option>
                        <option value="Excel"><?php echo __('Excel Spreadsheet'); ?></option>
                        <option value="Interactive"><?php echo __('Interactive Dashboard'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reportDescription"><?php echo __('Description'); ?></label>
                    <textarea id="reportDescription" name="reportDescription" rows="3" 
                              placeholder="<?php echo __('Describe the purpose and scope of this report'); ?>"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeReportBuilder()">
                        <?php echo __('Cancel'); ?>
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-cog"></i> <?php echo __('Generate Report'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Chart data from PHP
        const monthlyDownloads = <?php echo json_encode($monthly_downloads_chart); ?>;
        const reportTypes = <?php echo json_encode($report_types); ?>;

        // Downloads trend chart
        const downloadsCtx = document.getElementById('downloadsChart').getContext('2d');
        new Chart(downloadsCtx, {
            type: 'line',
            data: {
                labels: Object.keys(monthlyDownloads),
                datasets: [{
                    label: '<?php echo __('Downloads'); ?>',
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
            
            // Show/hide category sections based on whether they have visible reports
            document.querySelectorAll('.category-section').forEach(section => {
                const hasVisibleReports = Array.from(section.querySelectorAll('.report-card'))
                    .some(card => card.style.display !== 'none');
                section.style.display = hasVisibleReports ? 'block' : 'none';
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
            alert(<?php echo json_encode(__('Report generation started! You will be notified when it\'s ready.')); ?>);
            closeReportBuilder();
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
    <script src="js/enhanced_reports.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof ReportManager !== 'undefined') {
                new ReportManager();
            }
        });
    </script>
</body>
</html>

