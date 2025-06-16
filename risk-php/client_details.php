<?php
// Professional and minimalistic client details page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Client Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }
        .container {
            background: white;
            max-width: 600px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
            padding: 30px 40px;
        }
        h1 {
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 24px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 8px;
            color: #2563eb;
        }
        dl {
            display: grid;
            grid-template-columns: 1fr 2fr;
            row-gap: 16px;
            column-gap: 24px;
        }
        dt {
            font-weight: 600;
            color: #374151;
            align-self: center;
        }
        dd {
            margin: 0;
            color: #4b5563;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Client Details</h1>
        <dl>
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
