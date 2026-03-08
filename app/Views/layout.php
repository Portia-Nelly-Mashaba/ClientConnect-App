<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($title ?? 'Client Connect') . ' | Client Connect', ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        :root {
            --bg-top: #0f172a;
            --bg-bottom: #1e293b;
            --panel: rgba(255, 255, 255, 0.14);
            --panel-strong: rgba(255, 255, 255, 0.2);
            --line: rgba(255, 255, 255, 0.25);
            --text: #e5e7eb;
            --muted: #cbd5e1;
            --primary: #adff2f;
            --primary-600: #8edb1c;
            --danger: #f87171;
            --danger-strong: #ef4444;
            --success-bg: rgba(16, 185, 129, 0.2);
            --error-bg: rgba(239, 68, 68, 0.2);
        }
        * { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            margin: 0;
            font-family: Inter, "Segoe UI", Tahoma, Arial, sans-serif;
            color: var(--text);
            background: linear-gradient(160deg, var(--bg-top), var(--bg-bottom));
        }
        a { color: inherit; }
        .container {
            width: min(1120px, 100% - 28px);
            margin: 22px auto 30px;
        }
        .subtitle {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 14px;
        }
        .glass {
            border: 1px solid var(--line);
            background: var(--panel);
            border-radius: 14px;
            backdrop-filter: blur(9px);
            box-shadow: 0 16px 40px rgba(2, 6, 23, 0.28);
        }
        .content-shell {
            padding: 16px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-bottom: 14px;
            padding: 16px;
        }
        .title {
            margin: 0;
            font-size: 24px;
            color: #fff;
        }
        .card {
            padding: 14px;
            margin-bottom: 12px;
        }
        .muted {
            color: var(--muted);
            margin: 8px 0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 14px;
            border: 1px solid transparent;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-primary {
            background: var(--primary);
            color: #052e12;
            border-color: var(--primary-600);
            box-shadow: none;
        }
        .btn-primary:hover {
            background: #9ff024;
            box-shadow: none;
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.12);
            color: #f8fafc;
            border-color: var(--line);
        }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.18); }
        .btn-danger {
            background: transparent;
            color: var(--danger);
            border-color: rgba(248, 113, 113, 0.7);
        }
        .btn-danger:hover {
            color: #fff;
            background: rgba(239, 68, 68, 0.25);
            border-color: var(--danger-strong);
        }
        .btn-open {
            border-color: rgba(173, 255, 47, 0.7);
            color: #e7ffc7;
            box-shadow: inset 0 0 0 1px rgba(173, 255, 47, 0.28);
        }
        .btn-open:hover {
            border-color: rgba(173, 255, 47, 0.95);
            background: rgba(173, 255, 47, 0.14);
            color: #f1ffd8;
        }
        .btn-link-action {
            border-color: rgba(173, 255, 47, 0.72);
            color: #ecffd5;
            box-shadow: inset 0 0 0 1px rgba(173, 255, 47, 0.24);
        }
        .btn-link-action:hover {
            border-color: rgba(173, 255, 47, 0.95);
            background: rgba(173, 255, 47, 0.12);
        }
        .btn-xs {
            font-size: 12px;
            padding: 5px 9px;
            border-radius: 8px;
        }
        .btn svg {
            width: 14px;
            height: 14px;
            flex: 0 0 auto;
        }
        .status, .error {
            border-radius: 10px;
            padding: 10px 12px;
            margin: 8px 0 12px;
            border: 1px solid transparent;
        }
        .flash-message {
            transition: opacity 260ms ease, transform 260ms ease;
        }
        .status {
            background: var(--success-bg);
            border-color: rgba(16, 185, 129, 0.6);
            color: #d1fae5;
        }
        .error {
            background: var(--error-bg);
            border-color: rgba(248, 113, 113, 0.6);
            color: #fecaca;
        }
        .error ul {
            margin: 0;
            padding-left: 18px;
        }
        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 640px;
        }
        th, td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            text-align: left;
            padding: 10px 8px;
            vertical-align: top;
        }
        th { color: #f8fafc; font-weight: 600; }
        .center { text-align: center; }
        .field { margin-bottom: 10px; }
        .field label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            color: #e2e8f0;
        }
        .field input,
        .field select {
            width: 100%;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: rgba(15, 23, 42, 0.45);
            color: #fff;
            padding: 9px 10px;
        }
        .field input[readonly] {
            color: #e2e8f0;
            cursor: default;
        }
        .kv {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
        .tabs-shell { margin-bottom: 10px; }
        .tabs {
            display: inline-flex;
            gap: 8px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            padding: 5px;
        }
        .tab-button {
            display: inline-block;
            text-decoration: none;
            font-size: 13px;
            color: #dbeafe;
            border-radius: 999px;
            padding: 7px 12px;
        }
        .tab-button.active {
            background: rgba(173, 255, 47, 0.18);
            border: 1px solid rgba(173, 255, 47, 0.55);
            color: #fff;
        }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .link-row {
            display: grid;
            gap: 10px;
            grid-template-columns: 1fr auto;
            align-items: end;
            margin-bottom: 12px;
        }
        .link-row .field {
            margin-bottom: 0;
        }
        .link-row .btn {
            height: 40px;
            margin-bottom: 0;
        }
        .inline-form { display: inline; }
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            padding: 12px;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            width: min(560px, 100%);
            padding: 16px;
        }
        .modal-actions {
            margin-top: 14px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
        @media (max-width: 700px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            .link-row {
                grid-template-columns: 1fr;
            }
            .content-shell {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <main class="content-shell">
        <?= $content ?? '' ?>
    </main>
</div>

<script>
    (function () {
        function openModal(selector) {
            var modal = document.querySelector(selector);
            if (modal) {
                modal.classList.add('open');
            }
        }

        function closeModal(selector) {
            var modal = document.querySelector(selector);
            if (modal) {
                modal.classList.remove('open');
            }
        }

        document.querySelectorAll('[data-open-modal]').forEach(function (button) {
            button.addEventListener('click', function () {
                openModal(button.getAttribute('data-open-modal'));
            });
        });

        document.querySelectorAll('[data-close-modal]').forEach(function (button) {
            button.addEventListener('click', function () {
                closeModal(button.getAttribute('data-close-modal'));
            });
        });

        document.querySelectorAll('[data-modal-overlay]').forEach(function (overlay) {
            overlay.addEventListener('click', function (event) {
                if (event.target === overlay) {
                    overlay.classList.remove('open');
                }
            });
        });

        document.querySelectorAll('[data-tab-button]').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                var group = button.getAttribute('data-tab-group');
                var target = button.getAttribute('data-tab-target');
                if (!group || !target) {
                    return;
                }

                document.querySelectorAll('[data-tab-button][data-tab-group="' + group + '"]').forEach(function (btn) {
                    btn.classList.remove('active');
                });
                document.querySelectorAll('[data-tab-panel][data-tab-group="' + group + '"]').forEach(function (panel) {
                    panel.classList.remove('active');
                });

                button.classList.add('active');
                var panel = document.querySelector(target);
                if (panel) {
                    panel.classList.add('active');
                }
            });
        });

        document.querySelectorAll('.flash-message').forEach(function (message) {
            window.setTimeout(function () {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-3px)';
                window.setTimeout(function () {
                    if (message.parentNode) {
                        message.parentNode.removeChild(message);
                    }
                }, 280);
            }, 3500);
        });
    })();
</script>
</body>
</html>
