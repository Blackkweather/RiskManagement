<?php
session_start();

// Sample process data for demonstration
$processes = [
    [
        'id' => 1,
        'name' => 'User Authentication',
        'code' => 'AUTH001',
        'description' => 'Process for authenticating users and managing access control',
        'domaine_name' => 'Security Domain',
        'parent_name' => null,
        'entity_name' => 'IT Department',
        'project_name' => 'Customer Portal Redesign',
        'createdAt' => '2025-01-28',
        'activities_count' => 5,
        'risks_count' => 3
    ],
    [
        'id' => 2,
        'name' => 'Login Validation',
        'code' => 'LOGIN001',
        'description' => 'Validate user credentials and session management',
        'domaine_name' => 'Security Domain',
        'parent_name' => 'User Authentication',
        'entity_name' => 'Development Team',
        'project_name' => 'Customer Portal Redesign',
        'createdAt' => '2025-01-29',
        'activities_count' => 3,
        'risks_count' => 2
    ],
    [
        'id' => 3,
        'name' => 'Password Recovery',
        'code' => 'PASS001',
        'description' => 'Process for handling password reset requests',
        'domaine_name' => 'Security Domain',
        'parent_name' => 'User Authentication',
        'entity_name' => 'Development Team',
        'project_name' => 'Customer Portal Redesign',
        'createdAt' => '2025-01-29',
        'activities_count' => 4,
        'risks_count' => 1
    ],
    [
        'id' => 4,
        'name' => 'Transaction Processing',
        'code' => 'TXN001',
        'description' => 'Core process for handling financial transactions',
        'domaine_name' => 'Financial Operations',
        'parent_name' => null,
        'entity_name' => 'Finance Division',
        'project_name' => 'Mobile Banking App',
        'createdAt' => '2025-02-25',
        'activities_count' => 8,
        'risks_count' => 5
    ],
    [
        'id' => 5,
        'name' => 'Payment Validation',
        'code' => 'PAY001',
        'description' => 'Validate payment details and fraud detection',
        'domaine_name' => 'Financial Operations',
        'parent_name' => 'Transaction Processing',
        'entity_name' => 'Risk Management',
        'project_name' => 'Mobile Banking App',
        'createdAt' => '2025-02-26',
        'activities_count' => 3,
        'risks_count' => 2
    ],
    [
        'id' => 6,
        'name' => 'Compliance Reporting',
        'code' => 'COMP001',
        'description' => 'Generate regulatory compliance reports',
        'domaine_name' => 'Financial Operations',
        'parent_name' => 'Transaction Processing',
        'entity_name' => 'Compliance',
        'project_name' => 'Mobile Banking App',
        'createdAt' => '2025-02-26',
        'activities_count' => 2,
        'risks_count' => 1
    ],
    [
        'id' => 7,
        'name' => 'Inventory Management',
        'code' => 'INV001',
        'description' => 'Manage product inventory and stock levels',
        'domaine_name' => 'Operations',
        'parent_name' => null,
        'entity_name' => 'Operations',
        'project_name' => 'ERP System Implementation',
        'createdAt' => '2025-03-10',
        'activities_count' => 6,
        'risks_count' => 4
    ],
    [
        'id' => 8,
        'name' => 'Supply Chain Coordination',
        'code' => 'SUP001',
        'description' => 'Coordinate with suppliers and manage procurement',
        'domaine_name' => 'Operations',
        'parent_name' => 'Inventory Management',
        'entity_name' => 'Operations',
        'project_name' => 'ERP System Implementation',
        'createdAt' => '2025-03-11',
        'activities_count' => 4,
        'risks_count' => 3
    ]
];

$total_processes = count($processes);
$root_processes = count(array_filter($processes, function($process) { return $process['parent_name'] === null; }));
$total_activities = array_sum(array_column($processes, 'activities_count'));
$total_risks = array_sum(array_column($processes, 'risks_count'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processes Management - RiskGuard Pro</title>
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

        /* Process Hierarchy */
        .process-hierarchy {
            position: relative;
        }

        .process-name {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hierarchy-icon {
            color: var(--gray);
            font-size: 12px;
        }

        .parent-process {
            font-weight: 600;
            color: var(--dark);
        }

        .child-process {
            color: var(--gray);
            padding-left: 24px;
            position: relative;
        }

        .child-process::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 50%;
            width: 8px;
            height: 1px;
            background: var(--border);
        }

        .description {
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
                <span>RiskGuard Pro</span>
            </div>
            
            <nav>
                <ul>
                    <li><a href="index.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                    <li><a href="clients.php"><i class="fas fa-building"></i> Clients</a></li>
                    <li><a href="projects.php"><i class="fas fa-project-diagram"></i> Projects</a></li>
                    <li><a href="entities.php"><i class="fas fa-sitemap"></i> Entities</a></li>
                    <li><a href="processes.php" class="active"><i class="fas fa-cogs"></i> Processes</a></li>
                    <li><a href="risks.php"><i class="fas fa-exclamation-triangle"></i> Risks</a></li>
                    <li><a href="controls.php"><i class="fas fa-shield-check"></i> Controls</a></li>
                    <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
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
                    <h1>Processes Management</h1>
                </div>
                <div class="header-right">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">5</span>
                    </div>
                    <a href="logout.php" class="btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>
            
            <main class="main-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Processes</h3>
                        <div class="stat-value"><?php echo $total_processes; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-cogs"></i> All processes
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3>Root Processes</h3>
                        <div class="stat-value"><?php echo $root_processes; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-layer-group"></i> Top level
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Activities</h3>
                        <div class="stat-value"><?php echo $total_activities; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-tasks"></i> Sub-processes
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Risks</h3>
                        <div class="stat-value"><?php echo $total_risks; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-exclamation-triangle"></i> Being managed
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Business Processes</h3>
<a href="add_process.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add New Process
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="search-filter">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search processes..." id="searchInput">
                            </div>
                            <select class="filter-select" id="domaineFilter">
                                <option value="">All Domains</option>
                                <option value="Security Domain">Security Domain</option>
                                <option value="Financial Operations">Financial Operations</option>
                                <option value="Operations">Operations</option>
                            </select>
                            <select class="filter-select" id="projectFilter">
                                <option value="">All Projects</option>
                                <option value="Customer Portal Redesign">Customer Portal Redesign</option>
                                <option value="Mobile Banking App">Mobile Banking App</option>
                                <option value="ERP System Implementation">ERP System Implementation</option>
                            </select>
                        </div>
                        
                        <table class="table" id="processesTable">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Process Name</th>
                                    <th>Domain</th>
                                    <th>Entity</th>
                                    <th>Project</th>
                                    <th>Parent Process</th>
                                    <th>Activities</th>
                                    <th>Risks</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($processes as $process): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($process['code']); ?></strong></td>
                                    <td>
                                        <div class="process-hierarchy">
                                            <div class="process-name <?php echo $process['parent_name'] ? 'child-process' : 'parent-process'; ?>">
                                                <?php if ($process['parent_name']): ?>
                                                    <i class="fas fa-level-up-alt hierarchy-icon"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-cogs hierarchy-icon"></i>
                                                <?php endif; ?>
                                                <?php echo htmlspecialchars($process['name']); ?>
                                            </div>
                                            <div class="description">
                                                <?php echo htmlspecialchars($process['description']); ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($process['domaine_name']); ?></td>
                                    <td><?php echo htmlspecialchars($process['entity_name']); ?></td>
                                    <td><?php echo htmlspecialchars($process['project_name']); ?></td>
                                    <td>
                                        <?php if ($process['parent_name']): ?>
                                            <span style="color: var(--gray); font-size: 14px;">
                                                <?php echo htmlspecialchars($process['parent_name']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="color: var(--gray); font-style: italic;">Root Process</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $process['activities_count']; ?></td>
                                    <td><?php echo $process['risks_count']; ?></td>
                                    <td><?php echo date('M j, Y', strtotime($process['createdAt'])); ?></td>
                                    <td>
                                        <a href="process_details.php?id=<?php echo $process['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="process_edit.php?id=<?php echo $process['id']; ?>" class="btn btn-primary btn-sm" style="background: var(--warning);">
                                            <i class="fas fa-edit"></i> Edit
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
            const domaineFilter = document.getElementById('domaineFilter');
            const projectFilter = document.getElementById('projectFilter');
            const table = document.getElementById('processesTable');
            
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
                const domaineValue = domaineFilter.value;
                const projectValue = projectFilter.value;
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const processName = cells[1].textContent.toLowerCase();
                    const domaine = cells[2].textContent;
                    const project = cells[4].textContent;

                    const matchesSearch = processName.includes(searchTerm) || 
                                        cells[0].textContent.toLowerCase().includes(searchTerm);
                    const matchesDomaine = !domaineValue || domaine === domaineValue;
                    const matchesProject = !projectValue || project === projectValue;

                    if (matchesSearch && matchesDomaine && matchesProject) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterTable);
            domaineFilter.addEventListener('change', filterTable);
            projectFilter.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>

