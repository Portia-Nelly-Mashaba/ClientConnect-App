<?php
/** @var array{id?: int, name?: string, surname?: string, email?: string} $contact */
$contact = (isset($contact) && is_array($contact)) ? $contact : [];
/** @var array<int, array{id?: int, name?: string, client_code?: string}> $linkedClients */
$linkedClients = (isset($linkedClients) && is_array($linkedClients)) ? $linkedClients : [];
/** @var array<int, array{id?: int, name?: string, client_code?: string}> $availableClients */
$availableClients = (isset($availableClients) && is_array($availableClients)) ? $availableClients : [];
$openClientsTab = (isset($openClientsTab) && $openClientsTab === true);
$activeTab = $openClientsTab ? 'clients' : 'general';
$contactId = (int) ($contact['id'] ?? 0);
$statusMessage = is_string($statusMessage ?? null) ? $statusMessage : null;
$errorMessage = is_string($errorMessage ?? null) ? $errorMessage : null;
?>

<h2>Contact Details</h2>
<p><a href="/contacts">Back to Contacts</a></p>

<?php if ($statusMessage !== null): ?>
    <p style="color:#166534; margin: 10px 0;"><?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<?php if ($errorMessage !== null): ?>
    <p style="color:#b91c1c; margin: 10px 0;"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<div style="display:flex; gap:12px; margin-bottom: 14px;">
    <a href="?tab=general" style="text-decoration:none; padding:6px 10px; border-radius:6px; border:1px solid #e5e7eb; <?= $activeTab === 'general' ? 'background:#111827;color:#fff;' : 'color:#111827;' ?>">General</a>
    <a href="?tab=clients" style="text-decoration:none; padding:6px 10px; border-radius:6px; border:1px solid #e5e7eb; <?= $activeTab === 'clients' ? 'background:#111827;color:#fff;' : 'color:#111827;' ?>">Client(s)</a>
</div>

<?php if ($activeTab === 'general'): ?>
    <div style="display:grid; gap:12px; max-width:560px;">
        <div>
            <label for="contact-name" style="display:block; margin-bottom:4px;">Name</label>
            <input id="contact-name" type="text" readonly value="<?= htmlspecialchars((string) ($contact['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div>
            <label for="contact-surname" style="display:block; margin-bottom:4px;">Surname</label>
            <input id="contact-surname" type="text" readonly value="<?= htmlspecialchars((string) ($contact['surname'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
        <div>
            <label for="contact-email" style="display:block; margin-bottom:4px;">Email</label>
            <input id="contact-email" type="text" readonly value="<?= htmlspecialchars((string) ($contact['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
        </div>
    </div>
<?php else: ?>
    <?php if ($availableClients !== []): ?>
        <form action="/contacts/<?= htmlspecialchars((string) $contactId, ENT_QUOTES, 'UTF-8') ?>/clients" method="post" style="margin: 8px 0 14px 0; display:grid; gap:10px; max-width:560px;">
            <div>
                <label for="client_id" style="display:block; margin-bottom:4px;">Link client</label>
                <select id="client_id" name="client_id" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <?php foreach ($availableClients as $client): ?>
                        <option value="<?= htmlspecialchars((string) ($client['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars((string) (($client['name'] ?? '') . ' - ' . ($client['client_code'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <button type="submit" style="padding:8px 12px; border:1px solid #111827; background:#111827; color:#fff; border-radius:6px; cursor:pointer;">Link</button>
            </div>
        </form>
    <?php endif; ?>

    <?php if ($linkedClients === []): ?>
        <p>No contact(s) found.</p>
    <?php else: ?>
        <table style="width:100%; border-collapse: collapse;">
            <thead>
            <tr>
                <th style="text-align:left; border-bottom:1px solid #e5e7eb; padding:8px 6px;">Client name</th>
                <th style="text-align:left; border-bottom:1px solid #e5e7eb; padding:8px 6px;">Client code</th>
                <th style="text-align:left; border-bottom:1px solid #e5e7eb; padding:8px 6px;"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($linkedClients as $client): ?>
                <tr>
                    <td style="padding:8px 6px; border-bottom:1px solid #f3f4f6;">
                        <?= htmlspecialchars((string) ($client['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td style="padding:8px 6px; border-bottom:1px solid #f3f4f6;">
                        <?= htmlspecialchars((string) ($client['client_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td style="padding:8px 6px; border-bottom:1px solid #f3f4f6;">
                        <form action="/contacts/<?= htmlspecialchars((string) $contactId, ENT_QUOTES, 'UTF-8') ?>/clients/<?= htmlspecialchars((string) ($client['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>/unlink" method="post">
                            <button type="submit" style="padding:6px 10px; border:1px solid #dc2626; background:#fff; color:#dc2626; border-radius:6px; cursor:pointer;">Unlink</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif; ?>
