<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RiskGuard Pro - Plateforme de Gestion des Risques Gouvernementale</title>
    <meta name="description" content="Solution avancée de gestion des risques et de conformité pour les institutions gouvernementales et les entreprises publiques.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --secondary: #3b82f6;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --white: #ffffff;
            --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            --gradient-hero: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--gray-700);
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(15, 23, 42, 0.98);
            box-shadow: var(--shadow-lg);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--white);
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .logo i {
            font-size: 2rem;
            color: var(--accent);
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--accent);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .cta-button {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: var(--gradient-hero);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.15) 0%, transparent 30%),
                radial-gradient(circle at 80% 70%, rgba(6, 182, 212, 0.15) 0%, transparent 30%);
            opacity: 0.6;
            z-index: 1;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }

        .hero-text .highlight {
            background: linear-gradient(135deg, var(--accent), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-text p {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 16px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            padding: 16px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        .hero-visual {
            position: relative;
        }

        .dashboard-preview {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-xl);
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            color: var(--white);
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Features Section */
        .features {
            padding: 6rem 0;
            background: var(--gray-50);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.25rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid var(--gray-200);
            transform: translateY(0);
            opacity: 1;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            background: var(--gradient-primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: rotate(10deg) scale(1.1);
        }

        .feature-icon i {
            font-size: 1.5rem;
            color: var(--white);
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .feature-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary);
            transition: width 0.5s ease;
        }

        .feature-card:hover .feature-title::after {
            width: 100px;
        }

        .feature-description {
            color: var(--gray-600);
            line-height: 1.6;
            transition: color 0.3s ease;
        }

        .feature-card:hover .feature-description {
            color: var(--gray-800);
        }

        /* Technologies Section */
        .technologies {
            padding: 6rem 0;
            background: var(--white);
        }

        .tech-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .tech-card {
            background: var(--gray-50);
            padding: 2rem;
            border-radius: 12px;
            border-left: 4px solid var(--primary);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: translateY(0);
            opacity: 1;
        }

        .tech-card:hover {
            background: var(--white);
            box-shadow: var(--shadow-lg);
            transform: translateX(8px);
        }

        .tech-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .tech-description {
            color: var(--gray-600);
            font-size: 0.95rem;
        }

        /* Stats Section */
        .stats {
            padding: 6rem 0;
            background: var(--gradient-hero);
            color: var(--white);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item {
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 0.5rem;
            display: block;
        }

        .stat-text {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* CTA Section */
        .cta-section {
            padding: 6rem 0;
            background: var(--primary);
            color: var(--white);
            text-align: center;
        }

        .cta-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-content p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-white {
            background: var(--white);
            color: var(--primary);
            padding: 16px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
        }

        /* Mobile Menu */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
            .nav-menu {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background: var(--dark);
                flex-direction: column;
                justify-content: flex-start;
                padding: 2rem;
                transition: left 0.3s ease;
            }
            .nav-menu.active {
                left: 0;
            }
            .hero-content {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }
            .hero-text h1 {
                font-size: 2.5rem;
            }
            .features-grid {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            /* Make Connexion button full width and add margin on mobile */
            .cta-button {
                width: 100%;
                margin-top: 1rem;
                text-align: center;
                box-sizing: border-box;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .dashboard-preview {
            animation: float 6s ease-in-out infinite;
        }

        /* Scroll animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Contact Section */
        .contact {
            padding: 6rem 0;
            background: var(--gray-50);
        }

        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            margin-bottom: 4rem;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .contact-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .contact-icon i {
            font-size: 1.5rem;
            color: var(--white);
        }

        .contact-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .contact-card p {
            color: var(--gray-600);
            line-height: 1.6;
        }

        /* Contact Form */
        .contact-form-container {
            background: var(--white);
            padding: 3rem;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }

        .checkbox-group {
            flex-direction: row;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .checkbox-label input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        .btn-submit {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 16px 32px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Map Section */
        .map-section {
            margin-top: 4rem;
        }

        .map-section h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .map-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .map-placeholder {
            height: 400px;
            background: linear-gradient(135deg, var(--gray-200) 0%, var(--gray-300) 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .map-overlay {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .map-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .map-info i {
            font-size: 2rem;
            color: var(--primary);
        }

        .map-info h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .map-info p {
            color: var(--gray-600);
            margin-bottom: 0.5rem;
        }

        .map-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .map-link:hover {
            text-decoration: underline;
        }

        /* Footer */
        .footer {
            background: var(--gray-900);
            color: var(--white);
        }

        .footer-content {
            padding: 4rem 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .footer-section h3.footer-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--white);
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-logo i {
            font-size: 2rem;
            color: var(--accent);
        }

        .footer-description {
            color: var(--gray-400);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: var(--gray-800);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateY(-2px);
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .footer-links a {
            color: var(--gray-400);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--accent);
        }

        .contact-info-footer {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .contact-item i {
            color: var(--accent);
            margin-top: 0.25rem;
        }

        .contact-item span {
            color: var(--gray-400);
            line-height: 1.5;
        }

        /* Newsletter */
        .newsletter-section {
            background: var(--gray-800);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .newsletter-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .newsletter-text h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .newsletter-text p {
            color: var(--gray-400);
        }

        .newsletter-form {
            display: flex;
            gap: 0.5rem;
            min-width: 300px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid var(--gray-600);
            border-radius: 8px;
            background: var(--gray-700);
            color: var(--white);
        }

        .newsletter-form input::placeholder {
            color: var(--gray-400);
        }

        .newsletter-form button {
            padding: 12px 16px;
            background: var(--primary);
            border: none;
            border-radius: 8px;
            color: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .newsletter-form button:hover {
            background: var(--primary-dark);
        }

        /* Footer Bottom */
        .footer-bottom {
            border-top: 1px solid var(--gray-800);
            padding: 1.5rem 0;
        }

        .footer-bottom-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .copyright p {
            color: var(--gray-400);
            font-size: 0.9rem;
        }

        .footer-bottom-links {
            display: flex;
            gap: 2rem;
        }

        .footer-bottom-links a {
            color: var(--gray-400);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-bottom-links a:hover {
            color: var(--accent);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="logo">
                <i class="fas fa-shield-alt"></i>
                <span>RiskGuard Pro</span>
            </a>
            
            <ul class="nav-menu" id="nav-menu">
                <li><a href="#features" class="nav-link">Fonctionnalités</a></li>
                <li><a href="#technologies" class="nav-link">Technologies</a></li>
                <li><a href="#stats" class="nav-link">Performance</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
                <li><a href="login.php" class="cta-button">Connexion</a></li>
            </ul>
            
            <button class="mobile-menu-toggle" id="mobile-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text" data-aos="fade-right" data-aos-duration="1200" data-aos-delay="200">
                <h1>
                    Plateforme de <span class="highlight">Gestion des Risques</span> 
                    Nouvelle Génération
                </h1>
                <p>
                    Solution complète de gouvernance, risque et conformité (GRC) conçue spécialement 
                    pour les institutions gouvernementales et les entreprises publiques. 
                    Intégrez l'intelligence artificielle et les dernières technologies pour 
                    une gestion proactive des risques.
                </p>
                <div class="hero-buttons">
                    <a href="#demo" class="btn-primary">
                        <i class="fas fa-play"></i>
                        Voir la Démo
                    </a>
                    <a href="#features" class="btn-secondary">
                        En Savoir Plus
                    </a>
                </div>
            </div>
            
            <div class="hero-visual" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="300">
                <div class="dashboard-preview">
                    <div class="dashboard-header">
                        <h3>Tableau de Bord Exécutif</h3>
                        <span class="badge">Temps Réel</span>
                    </div>
                    <div class="dashboard-stats">
                        <div class="stat-card">
                            <div class="stat-value">247</div>
                            <div class="stat-label">Risques Identifiés</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">98.5%</div>
                            <div class="stat-label">Conformité</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">15</div>
                            <div class="stat-label">Risques Critiques</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">€2.3M</div>
                            <div class="stat-label">Économies Réalisées</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header" data-aos="fade-up" data-aos-delay="100">
                <h2 class="section-title">Fonctionnalités Avancées</h2>
                <p class="section-subtitle">
                    Des outils puissants pour une gestion complète des risques et de la conformité
                </p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="feature-title">Intelligence Artificielle</h3>
                    <p class="feature-description">
                        Analyse prédictive des risques avec machine learning, détection automatique 
                        des anomalies et recommandations intelligentes basées sur l'IA.
                    </p>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Surveillance Temps Réel</h3>
                    <p class="feature-description">
                        Monitoring continu des risques avec alertes instantanées, tableaux de bord 
                        en temps réel et notifications automatiques pour une réaction rapide.
                    </p>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon">
                        <i class="fas fa-shield-check"></i>
                    </div>
                    <h3 class="feature-title">Conformité Automatisée</h3>
                    <p class="feature-description">
                        Gestion automatisée de la conformité réglementaire avec mise à jour 
                        des frameworks, génération de rapports et suivi des obligations.
                    </p>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <h3 class="feature-title">Architecture Interconnectée</h3>
                    <p class="feature-description">
                        Intégration native avec les systèmes existants, API robustes et 
                        architecture modulaire pour une adaptation parfaite à votre environnement.
                    </p>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Analytics Avancés</h3>
                    <p class="feature-description">
                        Visualisations interactives, analyses de tendances et rapports 
                        personnalisables pour une prise de décision éclairée.
                    </p>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 class="feature-title">Collaboration Renforcée</h3>
                    <p class="feature-description">
                        Workflows collaboratifs, gestion des approbations et communication 
                        intégrée entre les équipes pour une gouvernance efficace.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Technologies Section -->
    <section class="technologies" id="technologies">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Technologies de Pointe</h2>
                <p class="section-subtitle">
                    Basé sur les dernières innovations en matière de GRC et de sécurité
                </p>
            </div>
            
            <div class="tech-grid">
                <div class="tech-card" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="tech-title">Intelligence Artificielle & Machine Learning</h3>
                    <p class="tech-description">
                        Algorithmes d'apprentissage automatique pour la prédiction des risques, 
                        l'analyse comportementale et l'optimisation des contrôles.
                    </p>
                </div>
                
                <div class="tech-card" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="tech-title">Architecture Cloud Native</h3>
                    <p class="tech-description">
                        Déploiement flexible sur cloud public, privé ou hybride avec 
                        scalabilité automatique et haute disponibilité.
                    </p>
                </div>
                
                <div class="tech-card" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="tech-title">Blockchain & Cryptographie</h3>
                    <p class="tech-description">
                        Sécurisation des données avec chiffrement avancé et traçabilité 
                        immutable des actions via la technologie blockchain.
                    </p>
                </div>
                
                <div class="tech-card" data-aos="fade-up" data-aos-delay="400">
                    <h3 class="tech-title">API-First Design</h3>
                    <p class="tech-description">
                        Architecture orientée API pour une intégration seamless avec 
                        vos systèmes existants et une extensibilité maximale.
                    </p>
                </div>
                
                <div class="tech-card" data-aos="fade-up" data-aos-delay="500">
                    <h3 class="tech-title">Zero Trust Security</h3>
                    <p class="tech-description">
                        Modèle de sécurité Zero Trust avec authentification multi-facteurs, 
                        contrôle d'accès granulaire et surveillance continue.
                    </p>
                </div>
                
                <div class="tech-card" data-aos="fade-up" data-aos-delay="600">
                    <h3 class="tech-title">Conformité Réglementaire</h3>
                    <p class="tech-description">
                        Support natif pour ISO 27001, SOX, GDPR, NIST Cybersecurity Framework 
                        et autres standards internationaux.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats" id="stats">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Performance Prouvée</h2>
                <p class="section-subtitle">
                    Des résultats mesurables pour nos clients gouvernementaux
                </p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                    <span class="stat-number">50+</span>
                    <span class="stat-text">Institutions Gouvernementales</span>
                </div>
                
                <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                    <span class="stat-number">99.9%</span>
                    <span class="stat-text">Disponibilité Système</span>
                </div>
                
                <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                    <span class="stat-number">75%</span>
                    <span class="stat-text">Réduction des Risques</span>
                </div>
                
                <div class="stat-item" data-aos="fade-up" data-aos-delay="400">
                    <span class="stat-number">€50M+</span>
                    <span class="stat-text">Économies Générées</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content" data-aos="fade-up">
                <h2>Prêt à Transformer Votre Gestion des Risques ?</h2>
                <p>
                    Rejoignez les institutions gouvernementales qui font confiance à RiskGuard Pro 
                    pour sécuriser leur avenir numérique.
                </p>
                <div class="cta-buttons">
                    <a href="#contact" class="btn-white">
                        <i class="fas fa-calendar"></i>
                        Planifier une Démo
                    </a>
                    <a href="#" class="btn-secondary">
                        <i class="fas fa-download"></i>
                        Télécharger la Brochure
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Contactez-Nous</h2>
                <p class="section-subtitle">
                    Notre équipe d'experts est à votre disposition pour vous accompagner
                </p>
            </div>
            
            <div class="contact-content">
                <div class="contact-info" data-aos="fade-right">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Adresse</h3>
                        <p>
                            123 Avenue de la République<br>
                            75011 Paris, France
                        </p>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3>Téléphone</h3>
                        <p>
                            +33 1 23 45 67 89<br>
                            +33 1 23 45 67 90
                        </p>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email</h3>
                        <p>
                            contact@riskguard.gov<br>
                            support@riskguard.gov
                        </p>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Horaires</h3>
                        <p>
                            Lun - Ven: 8h00 - 18h00<br>
                            Support 24/7 disponible
                        </p>
                    </div>
                </div>
                
                <div class="contact-form-container" data-aos="fade-left">
                    <form class="contact-form" id="contactForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">Prénom *</label>
                                <input type="text" id="firstName" name="firstName" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Nom *</label>
                                <input type="text" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Téléphone</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="organization">Organisation *</label>
                            <input type="text" id="organization" name="organization" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Sujet *</label>
                            <select id="subject" name="subject" required>
                                <option value="">Sélectionnez un sujet</option>
                                <option value="demo">Demande de démonstration</option>
                                <option value="pricing">Informations tarifaires</option>
                                <option value="implementation">Mise en œuvre</option>
                                <option value="support">Support technique</option>
                                <option value="partnership">Partenariat</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="5" required 
                                placeholder="Décrivez vos besoins et objectifs..."></textarea>
                        </div>
                        
                        <div class="form-group checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="privacy" name="privacy" required>
                                <span class="checkmark"></span>
                                J'accepte la <a href="#" target="_blank">politique de confidentialité</a> *
                            </label>
                        </div>
                        
                        <div class="form-group checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="newsletter" name="newsletter">
                                <span class="checkmark"></span>
                                Je souhaite recevoir les actualités et mises à jour produit
                            </label>
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            Envoyer le Message
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Map Section -->
            <div class="map-section" data-aos="fade-up">
                <h3>Notre Localisation</h3>
                <div class="map-container">
                    <div id="map" class="map-placeholder">
                        <div class="map-overlay">
                            <div class="map-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <h4>RiskGuard Pro</h4>
                                    <p>123 Avenue de la République, 75011 Paris</p>
                                    <a href="https://maps.google.com/?q=123+Avenue+de+la+République,+75011+Paris" 
                                       target="_blank" class="map-link">
                                        Voir sur Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="container">
                <div class="footer-grid">
                    <!-- Company Info -->
                    <div class="footer-section">
                        <div class="footer-logo">
                            <i class="fas fa-shield-alt"></i>
                            <span>RiskGuard Pro</span>
                        </div>
                        <p class="footer-description">
                            Solution leader de gestion des risques et de conformité pour les 
                            institutions gouvernementales et les entreprises publiques.
                        </p>
                        <div class="social-links">
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="footer-section">
                        <h3 class="footer-title">Liens Rapides</h3>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <ul class="footer-links">
                            <li><a href="index.php">Tableau de Bord</a></li>
                            <li><a href="risks.php">Gestion des Risques</a></li>
                            <li><a href="reports.php">Rapports</a></li>
                            <li><a href="analytics.php">Analytics</a></li>
                            <li><a href="settings.php">Paramètres</a></li>
                        </ul>
                        <?php else: ?>
                        <ul class="footer-links">
                            <li><span style="color:#aaa;">Connectez-vous pour accéder aux liens rapides</span></li>
                        </ul>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Solutions -->
                    <div class="footer-section">
                        <h3 class="footer-title">Solutions</h3>
                        <ul class="footer-links">
                            <li><a href="solution_gouvernance.php">Gouvernance</a></li>
                            <li><a href="solution_risques.php">Gestion des Risques</a></li>
                            <li><a href="solution_conformite.php">Conformité</a></li>
                            <li><a href="solution_audit.php">Audit Interne</a></li>
                            <li><a href="solution_cybersecurite.php">Cybersécurité</a></li>
                        </ul>
                    </div>
                    
                    <!-- Resources -->
                    <div class="footer-section">
                        <h3 class="footer-title">Ressources</h3>
                        <ul class="footer-links">
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">API Reference</a></li>
                            <li><a href="#">Guides d'Utilisation</a></li>
                            <li><a href="#">Formations</a></li>
                            <li><a href="#">Support</a></li>
                        </ul>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="footer-section">
                        <h3 class="footer-title">Contact</h3>
                        <div class="contact-info-footer">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Avenue de la République<br>75011 Paris, France</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>+33 1 23 45 67 89</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>contact@riskguard.gov</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Newsletter -->
                <div class="newsletter-section">
                    <div class="newsletter-content">
                        <div class="newsletter-text">
                            <h3>Restez Informé</h3>
                            <p>Recevez les dernières actualités sur la gestion des risques et la conformité</p>
                        </div>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Votre adresse email" required>
                            <button type="submit">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <div class="copyright">
                        <p>&copy; 2025 RiskGuard Pro. Tous droits réservés.</p>
                    </div>
                    <div class="footer-bottom-links">
                        <a href="#">Politique de Confidentialité</a>
                        <a href="#">Conditions d'Utilisation</a>
                        <a href="#">Mentions Légales</a>
                        <a href="#">Cookies</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobile-toggle');
        const navMenu = document.getElementById('nav-menu');

        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    navMenu.classList.remove('active');
                    const icon = mobileToggle.querySelector('i');
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            });
        });

        // Counter animation for stats
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.stat-number');
                    statNumbers.forEach(stat => {
                        const finalValue = stat.textContent;
                        const numericValue = parseInt(finalValue.replace(/[^0-9]/g, ''));
                        const suffix = finalValue.replace(/[0-9]/g, '');
                        
                        if (!isNaN(numericValue)) {
                            animateCounter(stat, 0, numericValue, suffix, 2000);
                        }
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.stats');
        if (statsSection) {
            observer.observe(statsSection);
        }

        function animateCounter(element, start, end, suffix, duration) {
            const startTime = performance.now();
            
            function updateCounter(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                const current = Math.floor(start + (end - start) * easeOutQuart(progress));
                element.textContent = current + suffix;
                
                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                }
            }
            
            requestAnimationFrame(updateCounter);
        }

        function easeOutQuart(t) {
            return 1 - Math.pow(1 - t, 4);
        }

        // Add loading animation
        window.addEventListener('load', () => {
            document.body.classList.add('loaded');
        });
    </script>
</body>
</html>