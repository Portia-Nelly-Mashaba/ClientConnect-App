<?php
/** @var array<int, array{id?: int, name?: string, client_code?: string}> $clients */
$clients = (isset($clients) && is_array($clients)) ? $clients : [];
/** @var array<string, string> $errors */
$errors = (isset($errors) && is_array($errors)) ? $errors : [];
/** @var array<string, string> $old */
$old = (isset($old) && is_array($old)) ? $old : [];
$created = (isset($created) && $created === true);
?>

<h2>Clients</h2>

<?php if ($created): ?>
    <p style="color:#166534; margin: 10px 0;">Client created successfully.</p>
<?php endif; ?>

<?php if (isset($errors['general'])): ?>
    <p style="color:#b91c1c; margin: 10px 0;"><?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form action="/clients" method="post" style="margin: 16px 0 20px 0; display: grid; gap: 10px; max-width: 460px;">
    <div>
        <label for="name" style="display:block; margin-bottom: 4px;">Name</label>
        <input
            id="name"
            name="name"
            type="text"
            value="<?= htmlspecialchars((string) ($old['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
            style="width:100%; padding:8px; border: 1px solid #d1d5db; border-radius: 6px;"
        >
        <?php if (isset($errors['name'])): ?>
            <small style="color:#b91c1c;"><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></small>
        <?php endif; ?>
    </div>

    <div>
        <button
            type="submit"
            style="padding: 8px 12px; border: 1px solid #b91c1c; background: #b91c1c; color: #fff; border-radius: 6px; cursor: pointer;"
        >
            + New Client
        </button>
    </div>
</form>

<?php if ($clients === []): ?>
    <p>No clients found yet.</p>
<?php else: ?>
    <p>Showing <?= count($clients) ?> client(s).</p>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
        <tr>
            <th style="text-align:left; border-bottom: 1px solid #e5e7eb; padding: 8px 6px;">ID</th>
            <th style="text-align:left; border-bottom: 1px solid #e5e7eb; padding: 8px 6px;">Name</th>
            <th style="text-align:left; border-bottom: 1px solid #e5e7eb; padding: 8px 6px;">Client Code</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td style="padding: 8px 6px; border-bottom: 1px solid #f3f4f6;">
                    <?= htmlspecialchars((string) ($client['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </td>
                <td style="padding: 8px 6px; border-bottom: 1px solid #f3f4f6;">
                    <?= htmlspecialchars((string) ($client['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </td>
                <td style="padding: 8px 6px; border-bottom: 1px solid #f3f4f6;">
                    <?= htmlspecialchars((string) ($client['client_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
