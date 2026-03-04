<?php

declare(strict_types=1);

http_response_code(200);
header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Connect</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f7f9;
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px 28px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            max-width: 520px;
        }
        h1 {
            margin: 0 0 8px 0;
            font-size: 24px;
        }
        p {
            margin: 0;
            line-height: 1.5;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<main class="card">
    <h1>Client Connect running</h1>
    <p>Plain PHP scaffold is working from <code>public/index.php</code>.</p>
</main>
</body>
</html>

