<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - RiskGuard Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .navbar .nav-links {
            display: flex;
            gap: 20px;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .navbar .nav-links a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .profile-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: bold;
        }

        .profile-info h1 {
            color: #2d3748;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .profile-info .role {
            color: #667eea;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .profile-info .details {
            display: flex;
            gap: 2rem;
            color: #718096;
        }

        .profile-info .details div {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .profile-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #2d3748;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2d3748;
            font-weight: 600;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-label {
            color: #718096;
            font-size: 0.9rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-content {
            flex: 1;
        }

        .activity-content .title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }

        .activity-content .time {
            color: #718096;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-info .details {
                flex-direction: column;
                gap: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-shield-alt"></i>
            RiskGuard Pro
        </div>
        <div class="nav-links">
            <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="clients.php"><i class="fas fa-building"></i> Clients</a>
            <a href="risks.php"><i class="fas fa-exclamation-triangle"></i> Risks</a>
            <a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a>
            <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>

    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                AU
            </div>
            <div class="profile-info">
                <h1>Admin User</h1>
                <div class="role">System Administrator</div>
                <div class="details">
                    <div>
                        <i class="fas fa-envelope"></i>
                        admin@riskguard.com
                    </div>
                    <div>
                        <i class="fas fa-phone"></i>
                        +1 (555) 123-4567
                    </div>
                    <div>
                        <i class="fas fa-calendar"></i>
                        Joined Jan 2025
                    </div>
                </div>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">156</div>
                <div class="stat-label">Risks Managed</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">23</div>
                <div class="stat-label">Reports Generated</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">8</div>
                <div class="stat-label">Active Projects</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">94%</div>
                <div class="stat-label">System Uptime</div>
            </div>
        </div>

        <!-- Profile Content Grid -->
        <div class="profile-grid">
            <!-- Personal Information -->
            <div class="profile-section">
                <h2 class="section-title">
                    <i class="fas fa-user"></i>
                    Personal Information
                </h2>
                
                <form id="personalInfoForm">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" value="Admin">
                    </div>
                    
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" value="User">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="admin@riskguard.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="+1 (555) 123-4567">
                    </div>
                    
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department">
                            <option value="IT" selected>Information Technology</option>
                            <option value="Finance">Finance</option>
                            <option value="Operations">Operations</option>
                            <option value="HR">Human Resources</option>
                            <option value="Management">Management</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Update Information
                    </button>
                </form>
            </div>

            <!-- Security Settings -->
            <div class="profile-section">
                <h2 class="section-title">
                    <i class="fas fa-lock"></i>
                    Security Settings
                </h2>
                
                <form id="securityForm">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" name="currentPassword" placeholder="Enter current password">
                    </div>
                    
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
                    </div>
                    
                    <div class="form-group">
                        <label for="twoFactor">Two-Factor Authentication</label>
                        <select id="twoFactor" name="twoFactor">
                            <option value="disabled">Disabled</option>
                            <option value="sms">SMS</option>
                            <option value="email" selected>Email</option>
                            <option value="app">Authenticator App</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-shield-alt"></i>
                        Update Security
                    </button>
                </form>
            </div>

            <!-- Notification Preferences -->
            <div class="profile-section">
                <h2 class="section-title">
                    <i class="fas fa-bell"></i>
                    Notification Preferences
                </h2>
                
                <form id="notificationForm">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" checked> Email notifications for high-priority risks
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" checked> Weekly risk summary reports
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox"> SMS alerts for critical risks
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" checked> Project milestone notifications
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox"> System maintenance alerts
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Preferences
                    </button>
                </form>
            </div>

            <!-- Recent Activity -->
            <div class="profile-section">
                <h2 class="section-title">
                    <i class="fas fa-history"></i>
                    Recent Activity
                </h2>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="title">Created new risk assessment</div>
                        <div class="time">2 hours ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="activity-content">
                        <div class="title">Updated client profile</div>
                        <div class="time">5 hours ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="title">Generated monthly report</div>
                        <div class="time">1 day ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="activity-content">
                        <div class="title">Added new team member</div>
                        <div class="time">3 days ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="activity-content">
                        <div class="title">Updated system settings</div>
                        <div class="time">1 week ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form submission handlers
        document.getElementById('personalInfoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Personal information updated successfully!');
        });

        document.getElementById('securityForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword !== confirmPassword) {
                alert('New passwords do not match!');
                return;
            }
            
            alert('Security settings updated successfully!');
        });

        document.getElementById('notificationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Notification preferences saved successfully!');
        });
    </script>
</body>
</html>

