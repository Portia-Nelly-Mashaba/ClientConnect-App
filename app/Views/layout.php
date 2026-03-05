<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($title ?? 'Client Connect') . ' | Client Connect', ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        :root {
            --bg: #f3f4f6;
            --panel: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
            --accent: #b91c1c;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        .container {
            width: min(980px, 100% - 32px);
            margin: 28px auto;
        }
        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
        }
        header {
            margin-bottom: 16px;
        }
        h1 {
            margin: 0;
            color: var(--accent);
            font-size: 28px;
        }
        .subtitle {
            margin: 6px 0 0 0;
            color: var(--muted);
        }
        nav {
            margin: 16px 0 22px 0;
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        nav a {
            color: #111827;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            padding-bottom: 2px;
        }
        nav a:hover {
            border-bottom-color: var(--accent);
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Client Connect</h1>
        <p class="subtitle">Plain PHP MVC foundation</p>
    </header>

    <nav>
        <a href="/">Home</a>
        <a href="/clients">Clients</a>
        <a href="/contacts">Contacts</a>
        <a href="/health/db">DB Health</a>
    </nav>

    <main class="panel">
        <?= $content ?? '' ?>
    </main>
</div>
</body>
</html>
