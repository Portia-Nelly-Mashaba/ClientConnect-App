<?php
/** @var array<int, array{id?: int, name?: string, client_code?: string, contacts_count?: int}> $clients */
$clients = (isset($clients) && is_array($clients)) ? $clients : [];
/** @var array<int, array{id?: int, name?: string, surname?: string}> $contacts */
$contacts = (isset($contacts) && is_array($contacts)) ? $contacts : [];
/** @var array<int, array{client_id?: int, client_name?: string, contact_id?: int, contact_name?: string, contact_surname?: string}> $links */
$links = (isset($links) && is_array($links)) ? $links : [];
/** @var array<string, string> $errors */
$errors = (isset($errors) && is_array($errors)) ? $errors : [];
/** @var array<string, string> $old */
$old = (isset($old) && is_array($old)) ? $old : [];
$created = (isset($created) && $created === true);
$linkStatus = (string) ($linkStatus ?? '');
$linkMessages = [
    'linked' => ['color' => '#166534', 'text' => 'Contact linked to client successfully.'],
    'unlinked' => ['color' => '#166534', 'text' => 'Contact unlinked from client successfully.'],
    'exists' => ['color' => '#b91c1c', 'text' => 'This client-contact link already exists.'],
    'missing' => ['color' => '#b91c1c', 'text' => 'That client-contact link does not exist.'],
    'invalid' => ['color' => '#b91c1c', 'text' => 'Please select a valid client and contact.'],
    'error' => ['color' => '#b91c1c', 'text' => 'Unable to update link right now. Please try again.'],
];
?>

<h2>Clients</h2>

<?php if ($created): ?>
    <p style="color:#166534; margin: 10px 0;">Client created successfully.</p>
<?php endif; ?>

<?php if (isset($errors['general'])): ?>
    <p style="color:#b91c1c; margin: 10px 0;"><?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<?php if (isset($linkMessages[$linkStatus])): ?>
    <p style="color:<?= htmlspecialchars($linkMessages[$linkStatus]['color'], ENT_QUOTES, 'UTF-8') ?>; margin: 10px 0;">
        <?= htmlspecialchars($linkMessages[$linkStatus]['text'], ENT_QUOTES, 'UTF-8') ?>
    </p>
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

<?php if ($clients !== [] && $contacts !== []): ?>
    <h3 style="margin: 18px 0 8px 0;">Link Contacts to Clients</h3>
    <form action="/client-contacts/link" method="post" style="margin: 8px 0 10px 0; display: grid; gap: 10px; max-width: 560px;">
        <input type="hidden" name="return_to" value="/clients">
        <div>
            <label for="link_client_id" style="display:block; margin-bottom: 4px;">Client</label>
            <select id="link_client_id" name="client_id" style="width:100%; padding:8px; border: 1px solid #d1d5db; border-radius: 6px;">
                <?php foreach ($clients as $client): ?>
                    <option value="<?= htmlspecialchars((string) ($client['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars((string) ($client['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="link_contact_id" style="display:block; margin-bottom: 4px;">Contact</label>
            <select id="link_contact_id" name="contact_id" style="width:100%; padding:8px; border: 1px solid #d1d5db; border-radius: 6px;">
                <?php foreach ($contacts as $contact): ?>
                    <option value="<?= htmlspecialchars((string) ($contact['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars((string) (($contact['surname'] ?? '') . ' ' . ($contact['name'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex; gap:8px;">
            <button
                type="submit"
                style="padding: 8px 12px; border: 1px solid #b91c1c; background: #b91c1c; color: #fff; border-radius: 6px; cursor: pointer;"
            >
                Link
            </button>
        </div>
    </form>
<?php endif; ?>

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
            <th style="text-align:left; border-bottom: 1px solid #e5e7eb; padding: 8px 6px;">Linked Contacts</th>
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
                <td style="padding: 8px 6px; border-bottom: 1px solid #f3f4f6;">
                    <?= htmlspecialchars((string) ($client['contacts_count'] ?? 0), ENT_QUOTES, 'UTF-8') ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h3 style="margin: 20px 0 8px 0;">Current Links</h3>
<?php if ($links === []): ?>
    <p>No client-contact links yet.</p>
<?php else: ?>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
        <tr>
            <th style="text-align:left; border-bottom: 1px solid #e5e7eb; padding: 8px 6px;">Client</th>
            <th style="text-align:left; border-bottom: 1px solid #e5e7eb; padding: 8px 6px;">Contact</th>
            <th style="text-align:left; border-bottom: 1px solid #e5e7eb; padding: 8px 6px;">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($links as $link): ?>
            <tr>
                <td style="padding: 8px 6px; border-bottom: 1px solid #f3f4f6;">
                    <?= htmlspecialchars((string) ($link['client_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </td>
                <td style="padding: 8px 6px; border-bottom: 1px solid #f3f4f6;">
                    <?= htmlspecialchars((string) (($link['contact_surname'] ?? '') . ' ' . ($link['contact_name'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                </td>
                <td style="padding: 8px 6px; border-bottom: 1px solid #f3f4f6;">
                    <form action="/client-contacts/unlink" method="post" style="margin:0;">
                        <input type="hidden" name="client_id" value="<?= htmlspecialchars((string) ($link['client_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="contact_id" value="<?= htmlspecialchars((string) ($link['contact_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="return_to" value="/clients">
                        <button
                            type="submit"
                            style="padding: 6px 10px; border: 1px solid #dc2626; background: #fff; color: #dc2626; border-radius: 6px; cursor: pointer;"
                        >
                            Unlink
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
