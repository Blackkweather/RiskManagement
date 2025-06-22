<?php
// Gestion des Risques Solution Page
session_start();
$title = 'Gestion des Risques';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Risques - RiskGuard Pro</title>
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
        <h1><i class="fa-solid fa-shield-halved"></i> Gestion des Risques</h1>
        <img src="img/risk-illustration.jpg" alt="Gestion des Risques Illustration" class="solution-img">
        <div class="img-credit">Illustration: Freepik (<a href="https://www.freepik.com/" target="_blank">freepik.com</a>)</div>
        <div class="solution-section">
            <p>La gestion des risques vise à identifier, évaluer, traiter et surveiller les risques qui pourraient impacter la réalisation des objectifs de l’organisation. Notre module vous permet de :</p>
            <ul>
                <li><b>Cartographier dynamiquement les risques :</b> Visualisez tous les risques par processus, entité ou projet.</li>
                <li><b>Évaluer quantitativement et qualitativement :</b> Utilisez des matrices de scoring, des indicateurs et des analyses d’impact.</li>
                <li><b>Gérer les plans d’action :</b> Suivez l’avancement des mesures de mitigation et l’efficacité des contrôles.</li>
                <li><b>Recevoir des alertes en temps réel :</b> Soyez notifié dès qu’un risque évolue ou qu’un seuil critique est franchi.</li>
            </ul>
        </div>
        <div class="solution-section">
            <h2>Pourquoi la gestion des risques est-elle essentielle ?</h2>
            <p>Une gestion proactive des risques permet de limiter les pertes, d’anticiper les crises et de renforcer la résilience de l’organisation. Elle favorise aussi la conformité et la confiance des parties prenantes.</p>
        </div>
        <div class="solution-section">
            <h2>Exemple de cartographie des risques</h2>
            <img src="https://www.highcharts.com/samples/graphics/line-basic.svg" alt="Exemple de graphique Risques" class="solution-img">
            <p style="color:#666;font-size:0.95rem;">Exemple de visualisation de l’évolution des risques et de l’efficacité des plans d’action.</p>
        </div>
        <a href="presentation.php" style="color:#2563eb;">&larr; Retour à la présentation</a>
    </div>
</body>
</html>
