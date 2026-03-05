<?php
/** @var array<int, array{id?: int, name?: string, client_code?: string, contacts_count?: int}> $clients */
$clients = (isset($clients) && is_array($clients)) ? $clients : [];
/** @var array<string, string> $errors */
$errors = (isset($errors) && is_array($errors)) ? $errors : [];
/** @var array<string, string> $old */
$old = (isset($old) && is_array($old)) ? $old : [];
$statusMessage = is_string($statusMessage ?? null) ? $statusMessage : null;
$errorMessage = is_string($errorMessage ?? null) ? $errorMessage : null;
$openCreateModal = $errors !== [];
?>

<div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
    <h2 style="margin:0;">Clients</h2>
    <button type="button" data-open-modal="create-client-modal" style="padding: 8px 12px; border: 1px solid #b91c1c; background: #b91c1c; color: #fff; border-radius: 6px; cursor: pointer;">
        + New Client
    </button>
</div>

<?php if ($statusMessage !== null): ?>
    <p style="color:#166534; margin: 10px 0;"><?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<?php if ($errorMessage !== null): ?>
    <p style="color:#b91c1c; margin: 10px 0;"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<?php if (isset($errors['general']) && $errorMessage === null): ?>
    <p style="color:#b91c1c; margin: 10px 0;"><?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<?php if ($clients === []): ?>
    <p>No client(s) found.</p>
<?php else: ?>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
        <tr>
            <th style="text-align:left; border-bottom: 1px solid #e5e7eb; padding: 8px 6px;">Name</th>
            <th style="text-align:left; border-bottom: 1px solid #e5e7eb; padding: 8px 6px;">Client code</th>
            <th style="text-align:center; border-bottom: 1px solid #e5e7eb; padding: 8px 6px;">No. of linked contacts</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td style="padding: 8px 6px; border-bottom: 1px solid #f3f4f6;">
                    <a href="/clients/<?= htmlspecialchars((string) ($client['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars((string) ($client['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </td>
                <td style="padding: 8px 6px; border-bottom: 1px solid #f3f4f6;">
                    <?= htmlspecialchars((string) ($client['client_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </td>
                <td style="padding: 8px 6px; border-bottom: 1px solid #f3f4f6; text-align:center;">
                    <?= htmlspecialchars((string) ($client['contacts_count'] ?? 0), ENT_QUOTES, 'UTF-8') ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div id="create-client-modal" data-modal-overlay style="display:none; position:fixed; inset:0; background:rgba(17,24,39,0.45); align-items:center; justify-content:center; z-index:1000; padding:16px;">
    <div style="width:min(520px,100%); background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:18px;">
        <h3 style="margin-top:0;">Create Client</h3>
        <p style="color:#6b7280; margin: 6px 0 16px 0;">Only name is required. Client code is generated automatically.</p>
        <form action="/clients" method="post" style="display:grid; gap:10px;">
            <div>
                <label for="name" style="display:block; margin-bottom: 4px;">Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="<?= htmlspecialchars((string) ($old['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    maxlength="255"
                    style="width:100%; padding:8px; border: 1px solid #d1d5db; border-radius: 6px;"
                >
                <?php if (isset($errors['name'])): ?>
                    <small style="color:#b91c1c;"><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></small>
                <?php endif; ?>
            </div>
            <div style="display:flex; gap:8px; justify-content:flex-end;">
                <button type="button" data-close-modal="create-client-modal" style="padding: 8px 12px; border: 1px solid #d1d5db; background: #fff; color: #111827; border-radius: 6px; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 8px 12px; border: 1px solid #b91c1c; background: #b91c1c; color: #fff; border-radius: 6px; cursor: pointer;">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        function openModal(id) {
            var modal = document.getElementById(id);
            if (!modal) return;
            modal.style.display = 'flex';
        }
        function closeModal(id) {
            var modal = document.getElementById(id);
            if (!modal) return;
            modal.style.display = 'none';
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
                    overlay.style.display = 'none';
                }
            });
        });
        <?php if ($openCreateModal): ?>
        openModal('create-client-modal');
        <?php endif; ?>
    })();
</script>
