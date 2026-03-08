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

<div class="glass header">
    <div>
        <h1 class="title">Clients</h1>
        <p class="subtitle">ClientConnect dashboard</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="/contacts" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM16 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3 19a5 5 0 0 1 10 0M11 19a5 5 0 0 1 10 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Contacts
        </a>
        <button type="button" class="btn btn-primary" data-open-modal="#create-client-modal">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            New Client
        </button>
    </div>
</div>

<?php if ($statusMessage !== null): ?>
    <p class="status flash-message"><?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<?php if ($errors !== []): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $fieldError): ?>
                <li><?= htmlspecialchars($fieldError, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($errorMessage !== null): ?>
    <p class="error flash-message"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<section class="glass card">
    <div class="table-wrap">
        <?php if ($clients === []): ?>
            <p class="muted">No client(s) found.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Client code</th>
                    <th class="center">No. of linked contacts</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($client['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($client['client_code'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="center"><?= htmlspecialchars((string) ($client['contacts_count'] ?? 0), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a class="btn btn-secondary btn-open" href="/clients/<?= htmlspecialchars((string) ($client['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                Open
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 17L17 7M9 7h8v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<div id="create-client-modal" class="modal-overlay<?= $openCreateModal ? ' open' : '' ?>" data-modal-overlay>
    <div class="glass modal">
        <h2 style="margin-top: 0;">Create Client</h2>
        <p class="subtitle" style="margin: 6px 0 18px;">Client code is auto-generated after save.</p>
        <form action="/clients" method="post">
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="<?= htmlspecialchars((string) ($old['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" maxlength="255" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-close-modal="#create-client-modal">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12l5 5L20 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
