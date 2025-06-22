<?php
// Gouvernance Solution Page
session_start();
$title = 'Gouvernance';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gouvernance - RiskGuard Pro</title>
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
        <h1><i class="fa-solid fa-sitemap"></i> Gouvernance</h1>
        <img src="img/governance-illustration.jpg" alt="Gouvernance Illustration" class="solution-img">
        <div class="img-credit">Illustration: Freepik (<a href="https://www.freepik.com/" target="_blank">freepik.com</a>)</div>
        <div class="solution-section">
            <p>La gouvernance d’entreprise est l’ensemble des processus, règles et pratiques qui régissent la manière dont une organisation est dirigée et contrôlée. Notre module Gouvernance vous permet de :</p>
            <ul>
                <li><b>Centraliser les politiques et procédures :</b> Accédez à tous vos documents de gouvernance en un seul endroit, avec gestion des versions et des accès.</li>
                <li><b>Suivre les décisions et plans d’action :</b> Gardez l’historique des décisions stratégiques, assignez des tâches et suivez leur avancement.</li>
                <li><b>Organiser les comités et réunions :</b> Planifiez, documentez et archivez toutes les réunions de gouvernance.</li>
                <li><b>Assurer la traçabilité :</b> Toutes les actions sont tracées pour garantir la transparence et l’auditabilité.</li>
            </ul>
        </div>
        <div class="solution-section">
            <h2>Pourquoi la gouvernance est-elle cruciale ?</h2>
            <p>Une bonne gouvernance permet de renforcer la confiance des parties prenantes, d’améliorer la prise de décision et de réduire les risques organisationnels. Elle est aussi un levier essentiel pour la conformité réglementaire et la performance durable.</p>
        </div>
        <div class="solution-section">
            <h2>Exemple de pilotage visuel</h2>
            <img src="https://www.highcharts.com/samples/graphics/line-basic.svg" alt="Exemple de graphique Gouvernance" class="solution-img">
            <p style="color:#666;font-size:0.95rem;">Visualisation de l’évolution des actions de gouvernance et du suivi des décisions stratégiques.</p>
        </div>
        <a href="presentation.php" style="color:#2563eb;">&larr; Retour à la présentation</a>
    </div>
</body>
</html>
