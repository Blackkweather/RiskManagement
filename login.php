<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RiskGuard Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 500px;
        }

        .login-form {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-visual {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            text-align: center;
            padding: 40px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
        }

        .logo i {
            font-size: 3rem;
            color: #667eea;
        }

        .logo h1 {
            color: #2d3748;
            font-size: 2.5rem;
            font-weight: 700;
        }

        .welcome-text {
            margin-bottom: 30px;
        }

        .welcome-text h2 {
            font-size: 2rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .welcome-text p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .features {
            list-style: none;
            text-align: left;
            margin-top: 30px;
        }

        .features li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .features i {
            color: #48bb78;
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3748;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.1rem;
        }

        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .forgot-password:hover {
            color: #764ba2;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
            color: #a0aec0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 20px;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 400px;
            }
            
            .login-visual {
                display: none;
            }
            
            .login-form {
                padding: 40px 30px;
            }
            
            .logo h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
                <h1>RiskGuard Pro</h1>
            </div>

            <div class="error-message" id="errorMessage">
                Invalid credentials. Please try again.
            </div>

            <form id="loginForm" method="POST" action="api/auth.php">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" required 
                               placeholder="Enter your email address">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required 
                               placeholder="Enter your password">
                    </div>
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="login-btn" id="loginButton">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>
        </div>

        <div class="login-visual">
            <div class="welcome-text">
                <h2>Welcome Back!</h2>
                <p>Access your comprehensive risk management dashboard and stay in control of your organization's risk landscape.</p>
            </div>

            <ul class="features">
                <li>
                    <i class="fas fa-chart-line"></i>
                    Real-time Risk Analytics
                </li>
                <li>
                    <i class="fas fa-shield-alt"></i>
                    Advanced Security Controls
                </li>
                <li>
                    <i class="fas fa-users"></i>
                    Multi-user Collaboration
                </li>
                <li>
                    <i class="fas fa-file-alt"></i>
                    Comprehensive Reporting
                </li>
                <li>
                    <i class="fas fa-bell"></i>
                    Automated Alerts
                </li>
            </ul>
        </div>
    </div>

    <script>
        // Form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const loginButton = document.getElementById('loginButton');
            const errorMessage = document.getElementById('errorMessage');
            loginButton.disabled = true;
            loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
            errorMessage.style.display = 'none';
            // Send real AJAX request to api/auth.php
            fetch('api/auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            })
            .then(res => res.json())
            .then(data => {
                if (data.user) {
                    // Save user info to sessionStorage/localStorage if needed
                    // Redirect based on role
                    if (data.user.role === 'Client') {
                        window.location.href = 'client_dashboard.php';
                    } else if (data.user.role === 'Risk Manager') {
                        window.location.href = 'risk_manager_dashboard.php';
                    } else {
                        window.location.href = 'index.php';
                    }
                } else {
                    errorMessage.style.display = 'block';
                    loginButton.disabled = false;
                    loginButton.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In';
                }
            })
            .catch(() => {
                errorMessage.style.display = 'block';
                loginButton.disabled = false;
                loginButton.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In';
            });
        });

        // Input validation
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#e53e3e';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
        });

        // Password strength indicator (optional)
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            
            if (password.length > 0 && password.length < 6) {
                this.style.borderColor = '#e53e3e';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
        });
    </script>
</body>
</html>

