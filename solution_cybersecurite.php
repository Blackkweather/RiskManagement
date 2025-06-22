<?php
// Cybersécurité Solution Page
session_start();
$title = 'Cybersécurité';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cybersécurité - RiskGuard Pro</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .solution-container { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(37,99,235,0.08); padding: 32px 28px 24px 28px; }
        .solution-container h1 { color: #2563eb; margin-bottom: 18px; }
        .solution-container h2 { color: #1e40af; margin-top: 32px; }
        .solution-container ul { margin: 18px 0 18px 24px; }
        .solution-container p { color: #333; }
        .solution-img { width: 100%; border-radius: 10px; margin: 1.5rem 0; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .solution-section { margin-bottom: 2.5rem; }
        .img-credit { color: #888; font-size: 0.9rem; text-align: right; margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <div class="solution-container">
        <h1><i class="fa-solid fa-lock"></i> Cybersécurité</h1>
        <img src="img/cybersecurity-illustration.jpg" alt="Cybersécurité Illustration" class="solution-img">
        <div class="img-credit">Illustration: Freepik (<a href="https://www.freepik.com/" target="_blank">freepik.com</a>)</div>
        <div class="solution-section">
            <p>La cybersécurité vise à protéger les systèmes d’information, les données et les utilisateurs contre les menaces numériques. Notre module Cybersécurité vous permet de :</p>
            <ul>
                <li><b>Détecter et gérer les incidents :</b> Surveillez en temps réel, recevez des alertes et intervenez rapidement en cas d’incident.</li>
                <li><b>Renforcer les accès :</b> Mettez en place l’authentification forte et le contrôle d’accès granulaire.</li>
                <li><b>Surveiller les vulnérabilités :</b> Identifiez et corrigez les failles de sécurité avant qu’elles ne soient exploitées.</li>
                <li><b>Gérer les politiques de sécurité :</b> Centralisez la gestion des politiques et sensibilisez les utilisateurs.</li>
            </ul>
        </div>
        <div class="solution-section">
            <h2>Pourquoi la cybersécurité est-elle vitale ?</h2>
            <p>La cybersécurité protège l’organisation contre les cyberattaques, assure la continuité d’activité et garantit la confiance des clients et partenaires. Elle est indispensable pour respecter les normes et éviter les pertes financières.</p>
        </div>
        <div class="solution-section">
            <h2>Exemple de monitoring de sécurité</h2>
            <img src="https://www.highcharts.com/samples/graphics/line-basic.svg" alt="Exemple de graphique Cybersécurité" class="solution-img">
            <p style="color:#666;font-size:0.95rem;">Visualisation de l’état de la sécurité et du suivi des incidents détectés.</p>
        </div>
        <a href="presentation.php" style="color:#2563eb;">&larr; Retour à la présentation</a>
    </div>
</body>
</html>
