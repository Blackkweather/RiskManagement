<?php
// Conformité Solution Page
session_start();
$title = 'Conformité';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Conformité - RiskGuard Pro</title>
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
        <h1><i class="fa-solid fa-scale-balanced"></i> Conformité</h1>
        <img src="img/compliance-illustration.jpg" alt="Conformité Illustration" class="solution-img">
        <div class="img-credit">Illustration: Freepik (<a href="https://www.freepik.com/" target="_blank">freepik.com</a>)</div>
        <div class="solution-section">
            <p>La conformité consiste à s’assurer que l’organisation respecte l’ensemble des lois, règlements et normes applicables. Notre module Conformité vous aide à :</p>
            <ul>
                <li><b>Suivre les obligations réglementaires :</b> Centralisez et automatisez le suivi des exigences légales et normatives.</li>
                <li><b>Gérer les contrôles de conformité :</b> Planifiez, exécutez et documentez tous les contrôles nécessaires.</li>
                <li><b>Recevoir des alertes :</b> Soyez informé en cas de non-conformité ou d’échéance à venir.</li>
                <li><b>Générer des rapports :</b> Produisez facilement des rapports pour les audits et les autorités.</li>
            </ul>
        </div>
        <div class="solution-section">
            <h2>Pourquoi la conformité est-elle stratégique ?</h2>
            <p>La conformité protège l’organisation contre les sanctions, renforce la confiance des clients et partenaires, et valorise l’image de marque. Elle est aussi un gage de pérennité et de compétitivité.</p>
        </div>
        <div class="solution-section">
            <h2>Exemple de suivi de conformité</h2>
            <img src="https://www.highcharts.com/samples/graphics/line-basic.svg" alt="Exemple de graphique Conformité" class="solution-img">
            <p style="color:#666;font-size:0.95rem;">Visualisation de l’état de conformité et des contrôles réalisés.</p>
        </div>
        <a href="presentation.php" style="color:#2563eb;">&larr; Retour à la présentation</a>
    </div>
</body>
</html>
