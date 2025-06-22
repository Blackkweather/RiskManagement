<?php
// upload_excel.php
session_start();
// Only allow access to authorized users (e.g., admin or risk manager)
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['Admin', 'Risk Manager'])) {
    header('Location: login.php');
    exit();
}
$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['xlsx', 'xls'])) {
            $uploadPath = __DIR__ . '/uploads/' . basename($file['name']);
            if (!is_dir(__DIR__ . '/uploads')) {
                mkdir(__DIR__ . '/uploads', 0777, true);
            }
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $success = 'Fichier importé avec succès : ' . htmlspecialchars($file['name']);
                // Here you could call your Python script to process the file
                // shell_exec("python Import Xlsx.py " . escapeshellarg($uploadPath));
            } else {
                $error = "Erreur lors de l'enregistrement du fichier.";
            }
        } else {
            $error = 'Format de fichier non supporté. Veuillez choisir un fichier Excel (.xlsx ou .xls).';
        }
    } else {
        $error = 'Erreur lors de l’upload du fichier.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Importer un fichier Excel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .upload-container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(37,99,235,0.08); padding: 32px 28px 24px 28px; }
        .upload-container h1 { color: #2563eb; margin-bottom: 18px; }
        .upload-container form { display: flex; flex-direction: column; gap: 18px; }
        .upload-container input[type="file"] { padding: 8px; }
        .upload-container button { padding: 12px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1.1rem; font-weight: 600; cursor: pointer; }
        .upload-container button:hover { background: #1e40af; }
        .msg-success { color: #10b981; margin-bottom: 1rem; }
        .msg-error { color: #ef4444; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="upload-container">
        <h1>Importer un fichier Excel</h1>
        <?php if ($success): ?><div class="msg-success"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="msg-error"><?php echo $error; ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <label for="excel_file">Sélectionnez un fichier Excel (.xlsx ou .xls) :</label>
            <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls" required>
            <button type="submit">Importer</button>
        </form>
        <a href="presentation.php" style="color:#2563eb;display:block;margin-top:2rem;">&larr; Retour à la présentation</a>
    </div>
</body>
</html>
