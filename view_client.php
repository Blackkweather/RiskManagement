<?php
// Placeholder page for viewing client details
// In a real app, you would fetch client data by ID and display it
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
    </style>
</head>
<body>
    <div class="container">
        <h1>View Client</h1>
        <dl class="client-details">
            <dt>Denomination:</dt>
            <dd><!-- Client denomination here --></dd>

            <dt>Judicial:</dt>
            <dd><!-- Client judicial info here --></dd>

            <dt>Sector:</dt>
            <dd><!-- Client sector here --></dd>

            <dt>Code:</dt>
            <dd><!-- Client code here --></dd>

            <dt>Config:</dt>
            <dd><!-- Client config here --></dd>

            <dt>Appetency Active:</dt>
            <dd><!-- Client appetency active here --></dd>
        </dl>
    </div>
</body>
</html>
