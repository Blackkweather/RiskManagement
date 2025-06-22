<?php
session_start();
require_once 'lang/translation.php';
require_once 'config/database.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize variables
$risks = [];
$total_risks = 0;
$active_risks = 0;
$critical_risks = 0;
$high_risks = 0;

// Fetch risks from database
if ($db) {
    try {
        $query = "SELECT r.*, 
                         a.name as activity_name, 
                         e.name as entity_name,
                         p.name as project_name,
                         r.createdAt
                  FROM risk r
                  LEFT JOIN activity a ON r.activityId = a.id
                  LEFT JOIN entity e ON r.entityId = e.id
                  LEFT JOIN project p ON e.projectId = p.id
                  ORDER BY r.riskScore DESC, r.createdAt DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $risks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate statistics
        $total_risks = count($risks);
        $active_risks = count(array_filter($risks, function($risk) { return $risk['active']; }));
        $critical_risks = count(array_filter($risks, function($risk) { return $risk['riskScore'] >= 20; }));
        $high_risks = count(array_filter($risks, function($risk) { return $risk['riskScore'] >= 15 && $risk['riskScore'] < 20; }));
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        // Keep default values if database fails
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('Risks Management'); ?> - <?php echo __('RiskGuard Pro'); ?></title>
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

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-inactive {
            background: rgba(100, 116, 139, 0.1);
            color: var(--gray);
            border: 1px solid rgba(100, 116, 139, 0.2);
        }

        .risk-details {
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
                <span><?php echo __('RiskGuard Pro'); ?></span>
            </div>
            
            <nav>
                <ul>
                    <li><a href="index.php"><i class="fas fa-chart-line"></i> <?php echo __('Dashboard'); ?></a></li>
                    <li><a href="clients.php"><i class="fas fa-building"></i> <?php echo __('Clients'); ?></a></li>
                    <li><a href="projects.php"><i class="fas fa-project-diagram"></i> <?php echo __('Projects'); ?></a></li>
                    <li><a href="entities.php"><i class="fas fa-sitemap"></i> <?php echo __('Entities'); ?></a></li>
                    <li><a href="processes.php"><i class="fas fa-cogs"></i> <?php echo __('Processes'); ?></a></li>
                    <li><a href="risks.php" class="active"><i class="fas fa-exclamation-triangle"></i> <?php echo __('Risks'); ?></a></li>
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
                    <h1><?php echo __('Risks Management'); ?></h1>
                </div>
                <div class="header-right">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge"><?php echo $critical_risks; ?></span>
                    </div>
                    <a href="logout.php" class="btn">
                        <i class="fas fa-sign-out-alt"></i> <?php echo __('Logout'); ?>
                    </a>
                </div>
            </header>
            
            <main class="main-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?php echo __('Total Risks'); ?></h3>
                        <div class="stat-value"><?php echo $total_risks; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo __('All risks'); ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo __('Active Risks'); ?></h3>
                        <div class="stat-value"><?php echo $active_risks; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-eye"></i> <?php echo __('Being monitored'); ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo __('Critical Risks'); ?></h3>
                        <div class="stat-value"><?php echo $critical_risks; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-fire"></i> <?php echo __('Immediate attention'); ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo __('High Risks'); ?></h3>
                        <div class="stat-value"><?php echo $high_risks; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-warning"></i> <?php echo __('Priority monitoring'); ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo __('Risk Register'); ?></h3>
                        <a href="add_risk.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> <?php echo __('Add New Risk'); ?>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="search-filter">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="<?php echo __('Search risks...'); ?>" id="searchInput">
                            </div>
                            <select class="filter-select" id="criticalityFilter">
                                <option value=""><?php echo __('All Criticality'); ?></option>
                                <option value="critical"><?php echo __('Critical'); ?> (≥20)</option>
                                <option value="high"><?php echo __('High'); ?> (15-19)</option>
                                <option value="medium"><?php echo __('Medium'); ?> (10-14)</option>
                                <option value="low"><?php echo __('Low'); ?> (<10)</option>
                            </select>
                            <select class="filter-select" id="statusFilter">
                                <option value=""><?php echo __('All Status'); ?></option>
                                <option value="active"><?php echo __('Active'); ?></option>
                                <option value="inactive"><?php echo __('Inactive'); ?></option>
                            </select>
                            <select class="filter-select" id="projectFilter">
                                <option value=""><?php echo __('All Projects'); ?></option>
                                <option value="Customer Portal Redesign">Customer Portal Redesign</option>
                                <option value="Mobile Banking App">Mobile Banking App</option>
                                <option value="ERP System Implementation">ERP System Implementation</option>
                                <option value="E-commerce Platform">E-commerce Platform</option>
                            </select>
                        </div>
                        
                        <table class="table" id="risksTable">
                            <thead>
                                <tr>
                                    <th><?php echo __('Risk ID'); ?></th>
                                    <th><?php echo __('Risk Name'); ?></th>
                                    <th><?php echo __('Project'); ?></th>
                                    <th><?php echo __('Entity'); ?></th>
                                    <th><?php echo __('Brut Criticality'); ?></th>
                                    <th><?php echo __('Net Criticality'); ?></th>
                                    <th><?php echo __('Status'); ?></th>
                                    <th><?php echo __('Created'); ?></th>
                                    <th><?php echo __('Actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($risks as $risk): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($risk['code']); ?></strong></td>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($risk['name']); ?></strong>
                                            <div class="risk-details">
                                                <?php echo htmlspecialchars(substr($risk['description'], 0, 80)) . '...'; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($risk['project_name']); ?></td>
                                    <td><?php echo htmlspecialchars($risk['entity_name']); ?></td>
                                    <td>
                                        <?php 
                                        $brutCriticality = $risk['brutCriticality'];
                                        $status_class = 'status-low';
                                        $status_text = 'Low';
                                        
                                        if ($brutCriticality >= 20) {
                                            $status_class = 'status-critical';
                                            $status_text = __('Critical');
                                        } elseif ($brutCriticality >= 15) {
                                            $status_class = 'status-high';
                                            $status_text = __('High');
                                        } elseif ($brutCriticality >= 10) {
                                            $status_class = 'status-medium';
                                            $status_text = __('Medium');
                                        } else {
                                            $status_text = __('Low');
                                        }
                                        ?>
                                        <span class="status-badge <?php echo $status_class; ?>">
                                            <?php echo $status_text; ?> (<?php echo $brutCriticality; ?>)
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $netCriticality = $risk['netCriticality'];
                                        $net_status_class = 'status-low';
                                        $net_status_text = 'Low';
                                        
                                        if ($netCriticality >= 20) {
                                            $net_status_class = 'status-critical';
                                            $net_status_text = __('Critical');
                                        } elseif ($netCriticality >= 15) {
                                            $net_status_class = 'status-high';
                                            $net_status_text = __('High');
                                        } elseif ($netCriticality >= 10) {
                                            $net_status_class = 'status-medium';
                                            $net_status_text = __('Medium');
                                        } else {
                                            $net_status_text = __('Low');
                                        }
                                        ?>
                                        <span class="status-badge <?php echo $net_status_class; ?>">
                                            <?php echo $net_status_text; ?> (<?php echo $netCriticality; ?>)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $risk['active'] ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo $risk['active'] ? __('Active') : __('Inactive'); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($risk['createdAt'])); ?></td>
                                    <td>
                                        <a href="risk_detail.php?id=<?php echo $risk['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> <?php echo __('View'); ?>
                                        </a>
                                        <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] === 'Admin' || $_SESSION['user']['role'] === 'RiskManager')): ?>
                                        <a href="risk_edit.php?id=<?php echo $risk['id']; ?>" class="btn btn-primary btn-sm" style="background: var(--warning);">
                                            <i class="fas fa-edit"></i> <?php echo __('Edit'); ?>
                                        </a>
                                        <form method="POST" action="api/risk.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this risk?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $risk['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" style="background: var(--danger); color: #fff; border: none; padding: 6px 12px; border-radius: 6px; margin-left: 4px;">
                                                <i class="fas fa-trash"></i> <?php echo __('Delete'); ?>
                                            </button>
                                        </form>
                                        <?php endif; ?>
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
            const criticalityFilter = document.getElementById('criticalityFilter');
            const statusFilter = document.getElementById('statusFilter');
            const projectFilter = document.getElementById('projectFilter');
            const table = document.getElementById('risksTable');
            
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
                const criticalityValue = criticalityFilter.value;
                const statusValue = statusFilter.value;
                const projectValue = projectFilter.value;
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const riskName = cells[1].textContent.toLowerCase();
                    const project = cells[2].textContent;
                    const brutCriticality = parseInt(cells[4].textContent.match(/\((\d+)\)/)[1]);
                    const status = cells[6].textContent.toLowerCase().trim();

                    const matchesSearch = riskName.includes(searchTerm) || 
                                        cells[0].textContent.toLowerCase().includes(searchTerm);
                    
                    let matchesCriticality = true;
                    if (criticalityValue) {
                        switch(criticalityValue) {
                            case 'critical':
                                matchesCriticality = brutCriticality >= 20;
                                break;
                            case 'high':
                                matchesCriticality = brutCriticality >= 15 && brutCriticality < 20;
                                break;
                            case 'medium':
                                matchesCriticality = brutCriticality >= 10 && brutCriticality < 15;
                                break;
                            case 'low':
                                matchesCriticality = brutCriticality < 10;
                                break;
                        }
                    }
                    
                    const matchesStatus = !statusValue || status.includes(statusValue);
                    const matchesProject = !projectValue || project === projectValue;

                    if (matchesSearch && matchesCriticality && matchesStatus && matchesProject) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterTable);
            criticalityFilter.addEventListener('change', filterTable);
            statusFilter.addEventListener('change', filterTable);
            projectFilter.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>

