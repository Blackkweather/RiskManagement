<?php
// Placeholder page for adding a new client
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add New Client</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .add-client-container {
            max-width: 480px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(37,99,235,0.08);
            padding: 32px 28px 24px 28px;
        }
        .add-client-container h1 {
            text-align: center;
            margin-bottom: 24px;
            color: #2563eb;
            font-size: 2rem;
        }
        .add-client-form label {
            font-weight: 600;
            margin-bottom: 4px;
            color: #2c3e50;
        }
        .add-client-form input,
        .add-client-form select,
        .add-client-form textarea {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #e0e7ef;
            border-radius: 6px;
            margin-bottom: 16px;
            background: #f8fafc;
            transition: border 0.2s;
        }
        .add-client-form input:focus,
        .add-client-form select:focus,
        .add-client-form textarea:focus {
            border: 1.5px solid #2563eb;
            outline: none;
        }
        .add-client-form button {
            padding: 12px;
            background: linear-gradient(90deg, #2563eb 60%, #1e40af 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            box-shadow: 0 2px 8px rgba(37,99,235,0.08);
            transition: background 0.2s;
        }
        .add-client-form button:hover {
            background: linear-gradient(90deg, #1e40af 60%, #2563eb 100%);
        }
    </style>
</head>
<body>
    <div class="add-client-container">
        <h1><i class="fa-solid fa-user-plus"></i> Add New Client</h1>
        <form class="add-client-form" method="POST" action="api/client.php">
            <label for="denomination">Denomination <span style="color:#e74c3c">*</span></label>
            <input type="text" id="denomination" name="denomination" required placeholder="e.g. Client Principal" />

            <label for="judicial">Judicial</label>
            <input type="text" id="judicial" name="judicial" placeholder="e.g. SARL, SA, etc." />

            <label for="sector">Sector</label>
            <input type="text" id="sector" name="sector" placeholder="e.g. Technologie, Finance..." />

            <label for="code">Code</label>
            <input type="text" id="code" name="code" placeholder="e.g. CLI001" />

            <label for="config">Config</label>
            <select id="config" name="config">
                <option value="NORMAL">Normal</option>
                <option value="COEFFIECCIENT">Coefficient</option>
                <option value="BASIC">Basic</option>
            </select>

            <label for="appetency_active">Appetency Active</label>
            <select id="appetency_active" name="appetency_active">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>

            <button type="submit"><i class="fa-solid fa-plus"></i> Add Client</button>
        </form>
    </div>
</body>
</html>
