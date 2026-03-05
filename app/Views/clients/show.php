<?php
/** @var array{id?: int, name?: string, client_code?: string} $client */
$client = (isset($client) && is_array($client)) ? $client : [];
/** @var array<int, array{id?: int, name?: string, surname?: string, email?: string}> $linkedContacts */
$linkedContacts = (isset($linkedContacts) && is_array($linkedContacts)) ? $linkedContacts : [];
/** @var array<int, array{id?: int, name?: string, surname?: string}> $availableContacts */
$availableContacts = (isset($availableContacts) && is_array($availableContacts)) ? $availableContacts : [];
$openContactsTab = (isset($openContactsTab) && $openContactsTab === true);
$activeTab = $openContactsTab ? 'contacts' : 'general';
$clientId = (int) ($client['id'] ?? 0);
$statusMessage = is_string($statusMessage ?? null) ? $statusMessage : null;
$errorMessage = is_string($errorMessage ?? null) ? $errorMessage : null;
?>

<h2>Client Details</h2>
<p><a href="/clients">Back to Clients</a></p>

<?php if ($statusMessage !== null): ?>
    <p style="color:#166534; margin: 10px 0;"><?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<?php if ($errorMessage !== null): ?>
    <p style="color:#b91c1c; margin: 10px 0;"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<div style="display:flex; gap:12px; margin-bottom: 14px;">
    <a href="?tab=general" style="text-decoration:none; padding:6px 10px; border-radius:6px; border:1px solid #e5e7eb; <?= $activeTab === 'general' ? 'background:#111827;color:#fff;' : 'color:#111827;' ?>">General</a>
    <a href="?tab=contacts" style="text-decoration:none; padding:6px 10px; border-radius:6px; border:1px solid #e5e7eb; <?= $activeTab === 'contacts' ? 'background:#111827;color:#fff;' : 'color:#111827;' ?>">Contact(s)</a>
</div>

<?php if ($activeTab === 'general'): ?>
    <div style="display:grid; gap:12px; max-width:560px;">
        <div>
            <label for="client-name" style="display:block; margin-bottom:4px;">Name</label>
            <input id="client-name" type="text" readonly value="<?= htmlspecialchars((string) ($client['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div>
            <label for="client-code" style="display:block; margin-bottom:4px;">Client code</label>
            <input id="client-code" type="text" readonly value="<?= htmlspecialchars((string) ($client['client_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
    </div>
<?php else: ?>
    <?php if ($availableContacts !== []): ?>
        <form action="/clients/<?= htmlspecialchars((string) $clientId, ENT_QUOTES, 'UTF-8') ?>/contacts" method="post" style="margin: 8px 0 14px 0; display:grid; gap:10px; max-width:560px;">
            <div>
                <label for="contact_id" style="display:block; margin-bottom:4px;">Link contact</label>
                <select id="contact_id" name="contact_id" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <?php foreach ($availableContacts as $contact): ?>
                        <option value="<?= htmlspecialchars((string) ($contact['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars((string) (($contact['surname'] ?? '') . ' ' . ($contact['name'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <button type="submit" style="padding:8px 12px; border:1px solid #111827; background:#111827; color:#fff; border-radius:6px; cursor:pointer;">Link</button>
            </div>
        </form>
    <?php endif; ?>

    <?php if ($linkedContacts === []): ?>
        <p>No contacts found.</p>
    <?php else: ?>
        <table style="width:100%; border-collapse: collapse;">
            <thead>
            <tr>
                <th style="text-align:left; border-bottom:1px solid #e5e7eb; padding:8px 6px;">Contact Full Name</th>
                <th style="text-align:left; border-bottom:1px solid #e5e7eb; padding:8px 6px;">Contact email address</th>
                <th style="text-align:left; border-bottom:1px solid #e5e7eb; padding:8px 6px;"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($linkedContacts as $contact): ?>
                <tr>
                    <td style="padding:8px 6px; border-bottom:1px solid #f3f4f6;">
                        <?= htmlspecialchars((string) (($contact['surname'] ?? '') . ' ' . ($contact['name'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td style="padding:8px 6px; border-bottom:1px solid #f3f4f6;">
                        <?= htmlspecialchars((string) ($contact['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td style="padding:8px 6px; border-bottom:1px solid #f3f4f6;">
                        <form action="/clients/<?= htmlspecialchars((string) $clientId, ENT_QUOTES, 'UTF-8') ?>/contacts/<?= htmlspecialchars((string) ($contact['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>/unlink" method="post">
                            <button type="submit" style="padding:6px 10px; border:1px solid #dc2626; background:#fff; color:#dc2626; border-radius:6px; cursor:pointer;">Unlink</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif; ?>
