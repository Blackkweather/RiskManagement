<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
require_once 'config/database.php';

// Get risk ID from query string
$riskId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$risk = null;
$error = null;
if ($riskId > 0) {
    try {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare('SELECT r.id, r.name, r.description, r.level, r.status, r.createdAt, r.updatedAt, c.denomination AS client, p.name AS project, e.name AS entity
            FROM Risk r
            LEFT JOIN ClientProfile c ON r.clientId = c.id
            LEFT JOIN Project p ON r.projectId = p.id
            LEFT JOIN Entity e ON r.entityId = e.id
            WHERE r.id = :id');
        $stmt->execute(['id' => $riskId]);
        $risk = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$risk) {
            $error = 'Risk not found.';
        }
    } catch (Exception $e) {
        $error = 'Could not load risk details.';
    }
} else {
    $error = 'Invalid risk ID.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Risk Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6fb; margin: 0; }
        .container { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 8px 32px rgba(60,72,88,0.12); padding: 40px; }
        h1 { color: #2563eb; margin-bottom: 24px; }
        .risk-details dt { font-weight: bold; color: #333; margin-top: 18px; }
        .risk-details dd { margin-left: 24px; margin-bottom: 10px; color: #444; }
        .back-link { display: inline-block; margin-top: 32px; color: #2563eb; text-decoration: none; font-weight: 500; }
        .back-link:hover { text-decoration: underline; }
        .error { color: #e53e3e; font-weight: bold; }
        .icon { margin-right: 8px; color: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fa-solid fa-triangle-exclamation icon"></i>Risk Details</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif ($risk): ?>
            <dl class="risk-details">
                <dt>ID:</dt>
                <dd><?php echo htmlspecialchars($risk['id']); ?></dd>
                <dt>Name:</dt>
                <dd><?php echo htmlspecialchars($risk['name']); ?></dd>
                <dt>Description:</dt>
                <dd><?php echo htmlspecialchars($risk['description']); ?></dd>
                <dt>Level:</dt>
                <dd><?php echo htmlspecialchars($risk['level']); ?></dd>
                <dt>Status:</dt>
                <dd><?php echo htmlspecialchars($risk['status']); ?></dd>
                <dt>Client:</dt>
                <dd><?php echo htmlspecialchars($risk['client']); ?></dd>
                <dt>Project:</dt>
                <dd><?php echo htmlspecialchars($risk['project']); ?></dd>
                <dt>Entity:</dt>
                <dd><?php echo htmlspecialchars($risk['entity']); ?></dd>
                <dt>Created At:</dt>
                <dd><?php echo htmlspecialchars($risk['createdAt']); ?></dd>
                <dt>Updated At:</dt>
                <dd><?php echo htmlspecialchars($risk['updatedAt']); ?></dd>
            </dl>
        <?php endif; ?>
        <a class="back-link" href="risks.php"><i class="fa-solid fa-arrow-left"></i> Back to Risks</a>
    </div>
</body>
</html>
