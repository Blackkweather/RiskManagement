<?php
// Placeholder page for editing a client
// In a real app, you would fetch client data by ID and populate the form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Client</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #2563eb; }
        .container { max-width: 600px; margin: auto; }
        form { display: flex; flex-direction: column; gap: 12px; }
        label { font-weight: bold; }
        input, select, textarea { padding: 8px; font-size: 16px; }
        button { padding: 10px; background-color: #2563eb; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #1e40af; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Client</h1>
        <form method="POST" action="api/client.php?id=<?php echo $_GET['id'] ?? ''; ?>">
            <label for="denomination">Denomination</label>
            <input type="text" id="denomination" name="denomination" value="" required />

            <label for="judicial">Judicial</label>
            <input type="text" id="judicial" name="judicial" value="" />

            <label for="sector">Sector</label>
            <input type="text" id="sector" name="sector" value="" />

            <label for="code">Code</label>
            <input type="text" id="code" name="code" value="" />

            <label for="config">Config</label>
            <textarea id="config" name="config"></textarea>

            <label for="appetency_active">Appetency Active</label>
            <select id="appetency_active" name="appetency_active">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>

            <button type="submit">Update Client</button>
        </form>
    </div>
</body>
</html>
