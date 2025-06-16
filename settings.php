<?php
session_start();

// Sample system settings data
$system_settings = [
    'general' => [
        'app_name' => 'RiskGuard Pro',
        'app_version' => '2.1.0',
        'timezone' => 'UTC',
        'language' => 'English',
        'date_format' => 'Y-m-d',
        'currency' => 'USD'
    ],
    'security' => [
        'session_timeout' => 30,
        'password_min_length' => 8,
        'require_2fa' => false,
        'login_attempts' => 5,
        'lockout_duration' => 15
    ],
    'notifications' => [
        'email_notifications' => true,
        'risk_alerts' => true,
        'report_notifications' => true,
        'system_updates' => false
    ],
    'backup' => [
        'auto_backup' => true,
        'backup_frequency' => 'daily',
        'retention_days' => 30,
        'last_backup' => '2025-06-15 02:00:00'
    ]
];

// Sample user management data
$users = [
    [
        'id' => 1,
        'name' => 'John Doe',
        'email' => 'john.doe@company.com',
        'role' => 'Risk Manager',
        'status' => 'Active',
        'last_login' => '2025-06-15 14:30',
        'created_at' => '2025-01-15'
    ],
    [
        'id' => 2,
        'name' => 'Jane Smith',
        'email' => 'jane.smith@company.com',
        'role' => 'Compliance Officer',
        'status' => 'Active',
        'last_login' => '2025-06-15 11:15',
        'created_at' => '2025-02-20'
    ],
    [
        'id' => 3,
        'name' => 'Mike Johnson',
        'email' => 'mike.johnson@company.com',
        'role' => 'Auditor',
        'status' => 'Active',
        'last_login' => '2025-06-14 16:45',
        'created_at' => '2025-03-10'
    ],
    [
        'id' => 4,
        'name' => 'Sarah Wilson',
        'email' => 'sarah.wilson@company.com',
        'role' => 'Risk Analyst',
        'status' => 'Inactive',
        'last_login' => '2025-06-10 09:20',
        'created_at' => '2025-04-05'
    ]
];

// System information
$system_info = [
    'server' => [
        'php_version' => phpversion(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize')
    ],
    'database' => [
        'type' => 'MySQL',
        'version' => '8.0.25',
        'size' => '45.2 MB',
        'tables' => 15,
        'last_optimized' => '2025-06-14 03:00:00'
    ],
    'storage' => [
        'total_space' => '100 GB',
        'used_space' => '12.5 GB',
        'free_space' => '87.5 GB',
        'usage_percentage' => 12.5
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - RiskGuard Pro</title>
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

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        }

        .btn-danger:hover {
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
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

        /* Settings Navigation */
        .settings-nav {
            display: flex;
            gap: 4px;
            margin-bottom: 32px;
            background: var(--white);
            padding: 8px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .settings-nav button {
            padding: 12px 20px;
            border: none;
            background: transparent;
            color: var(--gray);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 14px;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .settings-nav button.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
        }

        .settings-nav button:hover:not(.active) {
            background: var(--light);
            color: var(--dark);
        }

        /* Settings Sections */
        .settings-section {
            display: none;
        }

        .settings-section.active {
            display: block;
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
            background: linear-gradient(135deg, #fafbfc 0%, #f1f5f9 100%);
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.3px;
            margin-bottom: 4px;
        }

        .card-subtitle {
            font-size: 14px;
            color: var(--gray);
        }

        .card-body {
            padding: 28px;
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
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

        .form-group .help-text {
            font-size: 12px;
            color: var(--gray);
            margin-top: 4px;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--border);
            transition: 0.3s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: var(--primary);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
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
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
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

        /* System Info */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid var(--border);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--dark);
        }

        .info-value {
            color: var(--gray);
            font-family: monospace;
        }

        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background: var(--light);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
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
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            .header {
                padding: 0 20px 0 80px;
            }
            .settings-nav {
                flex-direction: column;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .info-grid {
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
                    <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
                    <li><a href="analytics.php"><i class="fas fa-chart-bar"></i> Analytics</a></li>
                    <li><a href="settings.php" class="active"><i class="fas fa-cog"></i> Settings</a></li>
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
                    <h1><i class="fas fa-cog"></i> System Settings</h1>
                </div>
                <div class="header-right">
                    <button class="btn btn-success" onclick="saveSettings()">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </header>

            <div class="main-content">
                <!-- Settings Navigation -->
                <div class="settings-nav">
                    <button class="active" onclick="showSection('general')">
                        <i class="fas fa-sliders-h"></i> General
                    </button>
                    <button onclick="showSection('security')">
                        <i class="fas fa-shield-alt"></i> Security
                    </button>
                    <button onclick="showSection('notifications')">
                        <i class="fas fa-bell"></i> Notifications
                    </button>
                    <button onclick="showSection('users')">
                        <i class="fas fa-users"></i> Users
                    </button>
                    <button onclick="showSection('backup')">
                        <i class="fas fa-database"></i> Backup
                    </button>
                    <button onclick="showSection('system')">
                        <i class="fas fa-info-circle"></i> System Info
                    </button>
                </div>

                <!-- General Settings -->
                <div class="settings-section active" id="general">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">General Settings</div>
                            <div class="card-subtitle">Configure basic application settings</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="app_name">Application Name</label>
                                    <input type="text" id="app_name" value="<?php echo $system_settings['general']['app_name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="timezone">Timezone</label>
                                    <select id="timezone">
                                        <option value="UTC" <?php echo $system_settings['general']['timezone'] === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                                        <option value="America/New_York">Eastern Time</option>
                                        <option value="America/Chicago">Central Time</option>
                                        <option value="America/Denver">Mountain Time</option>
                                        <option value="America/Los_Angeles">Pacific Time</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="language">Language</label>
                                    <select id="language">
                                        <option value="English" <?php echo $system_settings['general']['language'] === 'English' ? 'selected' : ''; ?>>English</option>
                                        <option value="Spanish">Spanish</option>
                                        <option value="French">French</option>
                                        <option value="German">German</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="date_format">Date Format</label>
                                    <select id="date_format">
                                        <option value="Y-m-d" <?php echo $system_settings['general']['date_format'] === 'Y-m-d' ? 'selected' : ''; ?>>YYYY-MM-DD</option>
                                        <option value="m/d/Y">MM/DD/YYYY</option>
                                        <option value="d/m/Y">DD/MM/YYYY</option>
                                        <option value="d-m-Y">DD-MM-YYYY</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <select id="currency">
                                        <option value="USD" <?php echo $system_settings['general']['currency'] === 'USD' ? 'selected' : ''; ?>>USD ($)</option>
                                        <option value="EUR">EUR (€)</option>
                                        <option value="GBP">GBP (£)</option>
                                        <option value="JPY">JPY (¥)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="settings-section" id="security">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Security Settings</div>
                            <div class="card-subtitle">Configure security and authentication settings</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="session_timeout">Session Timeout (minutes)</label>
                                    <input type="number" id="session_timeout" value="<?php echo $system_settings['security']['session_timeout']; ?>" min="5" max="480">
                                    <div class="help-text">Users will be automatically logged out after this period of inactivity</div>
                                </div>
                                <div class="form-group">
                                    <label for="password_min_length">Minimum Password Length</label>
                                    <input type="number" id="password_min_length" value="<?php echo $system_settings['security']['password_min_length']; ?>" min="6" max="32">
                                </div>
                                <div class="form-group">
                                    <label for="login_attempts">Maximum Login Attempts</label>
                                    <input type="number" id="login_attempts" value="<?php echo $system_settings['security']['login_attempts']; ?>" min="3" max="10">
                                </div>
                                <div class="form-group">
                                    <label for="lockout_duration">Lockout Duration (minutes)</label>
                                    <input type="number" id="lockout_duration" value="<?php echo $system_settings['security']['lockout_duration']; ?>" min="5" max="60">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" <?php echo $system_settings['security']['require_2fa'] ? 'checked' : ''; ?>>
                                        Require Two-Factor Authentication
                                    </label>
                                    <div class="help-text">Force all users to enable 2FA for enhanced security</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="settings-section" id="notifications">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Notification Settings</div>
                            <div class="card-subtitle">Configure system notifications and alerts</div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?php echo $system_settings['notifications']['email_notifications'] ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span style="margin-left: 16px; font-weight: 600;">Email Notifications</span>
                                <div class="help-text" style="margin-left: 66px;">Send email notifications for important events</div>
                            </div>
                            <div class="form-group">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?php echo $system_settings['notifications']['risk_alerts'] ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span style="margin-left: 16px; font-weight: 600;">Risk Alerts</span>
                                <div class="help-text" style="margin-left: 66px;">Notify when high-priority risks are identified</div>
                            </div>
                            <div class="form-group">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?php echo $system_settings['notifications']['report_notifications'] ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span style="margin-left: 16px; font-weight: 600;">Report Notifications</span>
                                <div class="help-text" style="margin-left: 66px;">Notify when reports are generated or updated</div>
                            </div>
                            <div class="form-group">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?php echo $system_settings['notifications']['system_updates'] ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span style="margin-left: 16px; font-weight: 600;">System Updates</span>
                                <div class="help-text" style="margin-left: 66px;">Notify about system maintenance and updates</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Management -->
                <div class="settings-section" id="users">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">User Management</div>
                            <div class="card-subtitle">Manage system users and their permissions</div>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 20px;">
                                <button class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add New User
                                </button>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($user['status']); ?>">
                                                <?php echo $user['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y H:i', strtotime($user['last_login'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm" style="margin-right: 8px;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Backup Settings -->
                <div class="settings-section" id="backup">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Backup & Recovery</div>
                            <div class="card-subtitle">Configure automatic backups and data recovery</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="toggle-switch">
                                        <input type="checkbox" <?php echo $system_settings['backup']['auto_backup'] ? 'checked' : ''; ?>>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span style="margin-left: 16px; font-weight: 600;">Enable Automatic Backups</span>
                                </div>
                                <div class="form-group">
                                    <label for="backup_frequency">Backup Frequency</label>
                                    <select id="backup_frequency">
                                        <option value="daily" <?php echo $system_settings['backup']['backup_frequency'] === 'daily' ? 'selected' : ''; ?>>Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="retention_days">Retention Period (days)</label>
                                    <input type="number" id="retention_days" value="<?php echo $system_settings['backup']['retention_days']; ?>" min="7" max="365">
                                </div>
                                <div class="form-group">
                                    <label>Last Backup</label>
                                    <div style="padding: 12px; background: var(--light); border-radius: 8px; color: var(--gray);">
                                        <?php echo date('M j, Y H:i', strtotime($system_settings['backup']['last_backup'])); ?>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: 24px;">
                                <button class="btn btn-success" style="margin-right: 12px;">
                                    <i class="fas fa-download"></i> Create Backup Now
                                </button>
                                <button class="btn">
                                    <i class="fas fa-upload"></i> Restore from Backup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="settings-section" id="system">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">System Information</div>
                            <div class="card-subtitle">View system status and technical details</div>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="card" style="margin-bottom: 0;">
                                    <div class="card-header">
                                        <div class="card-title" style="font-size: 16px;">Server Information</div>
                                    </div>
                                    <div class="card-body" style="padding: 20px;">
                                        <?php foreach ($system_info['server'] as $key => $value): ?>
                                        <div class="info-item">
                                            <div class="info-label"><?php echo ucwords(str_replace('_', ' ', $key)); ?></div>
                                            <div class="info-value"><?php echo htmlspecialchars($value); ?></div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <div class="card" style="margin-bottom: 0;">
                                    <div class="card-header">
                                        <div class="card-title" style="font-size: 16px;">Database Information</div>
                                    </div>
                                    <div class="card-body" style="padding: 20px;">
                                        <?php foreach ($system_info['database'] as $key => $value): ?>
                                        <div class="info-item">
                                            <div class="info-label"><?php echo ucwords(str_replace('_', ' ', $key)); ?></div>
                                            <div class="info-value"><?php echo htmlspecialchars($value); ?></div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <div class="card" style="margin-bottom: 0;">
                                    <div class="card-header">
                                        <div class="card-title" style="font-size: 16px;">Storage Information</div>
                                    </div>
                                    <div class="card-body" style="padding: 20px;">
                                        <?php foreach ($system_info['storage'] as $key => $value): ?>
                                        <div class="info-item">
                                            <div class="info-label"><?php echo ucwords(str_replace('_', ' ', $key)); ?></div>
                                            <div class="info-value">
                                                <?php if ($key === 'usage_percentage'): ?>
                                                    <?php echo $value; ?>%
                                                    <div class="progress-bar">
                                                        <div class="progress-fill" style="width: <?php echo $value; ?>%"></div>
                                                    </div>
                                                <?php else: ?>
                                                    <?php echo htmlspecialchars($value); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Settings navigation
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.settings-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Remove active class from all nav buttons
            document.querySelectorAll('.settings-nav button').forEach(button => {
                button.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }

        // Save settings function
        function saveSettings() {
            // Simulate saving settings
            const btn = event.target;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check"></i> Saved!';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 1000);
            }, 1500);
        }

        // Mobile menu toggle
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>

