<?php
// Audit Interne Solution Page
session_start();
$title = 'Audit Interne';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Audit Interne - RiskGuard Pro</title>
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
        <h1><i class="fa-solid fa-magnifying-glass-chart"></i> Audit Interne</h1>
        <img src="img/audit-illustration.jpg" alt="Audit Interne Illustration" class="solution-img">
        <div class="img-credit">Illustration: Freepik (<a href="https://www.freepik.com/" target="_blank">freepik.com</a>)</div>
        <div class="solution-section">
            <p>L’audit interne permet d’évaluer l’efficacité des processus, des contrôles et de la gestion des risques. Notre module Audit Interne vous permet de :</p>
            <ul>
                <li><b>Planifier et suivre les missions :</b> Organisez vos audits, assignez les équipes et suivez l’avancement en temps réel.</li>
                <li><b>Gérer les recommandations :</b> Centralisez les recommandations, suivez leur mise en œuvre et relancez les responsables.</li>
                <li><b>Tracer les anomalies :</b> Documentez et analysez toutes les anomalies détectées lors des audits.</li>
                <li><b>Automatiser le reporting :</b> Générez des rapports d’audit clairs et personnalisés.</li>
            </ul>
        </div>
        <div class="solution-section">
            <h2>Pourquoi l’audit interne est-il indispensable ?</h2>
            <p>L’audit interne contribue à l’amélioration continue, à la prévention des fraudes et à la sécurisation des actifs. Il offre une visibilité accrue à la direction et favorise la confiance des parties prenantes.</p>
        </div>
        <div class="solution-section">
            <h2>Exemple de suivi d’audit</h2>
            <img src="https://www.highcharts.com/samples/graphics/line-basic.svg" alt="Exemple de graphique Audit" class="solution-img">
            <p style="color:#666;font-size:0.95rem;">Visualisation de l’avancement des missions d’audit et du traitement des recommandations.</p>
        </div>
        <a href="presentation.php" style="color:#2563eb;">&larr; Retour à la présentation</a>
    </div>
</body>
</html>
