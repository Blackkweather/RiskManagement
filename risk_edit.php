<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'Admin' && $_SESSION['user']['role'] !== 'RiskManager')) {
    header('Location: login.php');
    exit();
}
require_once 'config/database.php';

$riskId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = null;
$success = null;
$risk = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $riskId > 0) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $level = trim($_POST['level']);
    $status = trim($_POST['status']);
    try {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare('UPDATE Risk SET name = :name, description = :description, level = :level, status = :status, updatedAt = NOW() WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'level' => $level,
            'status' => $status,
            'id' => $riskId
        ]);
        $success = 'Risk updated successfully.';
    } catch (Exception $e) {
        $error = 'Could not update risk.';
    }
}

// Fetch risk details
if ($riskId > 0) {
    try {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare('SELECT id, name, description, level, status FROM Risk WHERE id = :id');
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
    <title>Edit Risk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6fb; margin: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 8px 32px rgba(60,72,88,0.12); padding: 40px; }
        h1 { color: #2563eb; margin-bottom: 24px; }
        form label { font-weight: bold; display: block; margin-top: 18px; color: #333; }
        form input, form textarea, form select { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; margin-top: 6px; margin-bottom: 12px; }
        .btn { background: #2563eb; color: #fff; border: none; padding: 12px 28px; border-radius: 6px; font-size: 1rem; cursor: pointer; margin-top: 10px; }
        .btn:hover { background: #1d4ed8; }
        .back-link { display: inline-block; margin-top: 32px; color: #2563eb; text-decoration: none; font-weight: 500; }
        .back-link:hover { text-decoration: underline; }
        .error { color: #e53e3e; font-weight: bold; }
        .success { color: #16a34a; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fa-solid fa-pen-to-square"></i> Edit Risk</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif ($risk): ?>
            <?php if ($success): ?><p class="success"><?php echo htmlspecialchars($success); ?></p><?php endif; ?>
            <form method="post">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($risk['name']); ?>" required>
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($risk['description']); ?></textarea>
                <label for="level">Level</label>
                <select id="level" name="level" required>
                    <option value="Low" <?php if ($risk['level'] === 'Low') echo 'selected'; ?>>Low</option>
                    <option value="Medium" <?php if ($risk['level'] === 'Medium') echo 'selected'; ?>>Medium</option>
                    <option value="High" <?php if ($risk['level'] === 'High') echo 'selected'; ?>>High</option>
                </select>
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Open" <?php if ($risk['status'] === 'Open') echo 'selected'; ?>>Open</option>
                    <option value="In Progress" <?php if ($risk['status'] === 'In Progress') echo 'selected'; ?>>In Progress</option>
                    <option value="Closed" <?php if ($risk['status'] === 'Closed') echo 'selected'; ?>>Closed</option>
                </select>
                <button class="btn" type="submit"><i class="fa-solid fa-save"></i> Save Changes</button>
            </form>
        <?php endif; ?>
        <a class="back-link" href="risk_detail.php?id=<?php echo $riskId; ?>"><i class="fa-solid fa-arrow-left"></i> Back to Risk Details</a>
    </div>
</body>
</html>
