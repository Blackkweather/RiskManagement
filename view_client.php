<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Client') {
    header('Location: login.php');
    exit();
}
require_once 'config/database.php';

// Fetch client data for the logged-in client
$userId = $_SESSION['user']['id'];
$client = null;
try {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare('SELECT denomination, judicial, sector, code, config, appetency_active FROM client WHERE id = :id');
    $stmt->execute(['id' => $userId]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Could not load client details.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Client</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #2563eb; }
        .container { max-width: 600px; margin: auto; }
        .client-details { margin-top: 20px; }
        .client-details dt { font-weight: bold; margin-top: 10px; }
        .client-details dd { margin-left: 20px; margin-bottom: 10px; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Client</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif ($client): ?>
            <dl class="client-details">
                <dt>Denomination:</dt>
                <dd><?php echo htmlspecialchars($client['denomination']); ?></dd>

                <dt>Judicial:</dt>
                <dd><?php echo htmlspecialchars($client['judicial']); ?></dd>

                <dt>Sector:</dt>
                <dd><?php echo htmlspecialchars($client['sector']); ?></dd>

                <dt>Code:</dt>
                <dd><?php echo htmlspecialchars($client['code']); ?></dd>

                <dt>Config:</dt>
                <dd><?php echo htmlspecialchars($client['config']); ?></dd>

                <dt>Appetency Active:</dt>
                <dd><?php echo $client['appetency_active'] ? 'Yes' : 'No'; ?></dd>
            </dl>
        <?php else: ?>
            <p>No client data found.</p>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
