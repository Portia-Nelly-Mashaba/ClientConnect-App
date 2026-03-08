<?php
/** @var array<int, array{id?: int, name?: string, surname?: string, email?: string, clients_count?: int}> $contacts */
$contacts = (isset($contacts) && is_array($contacts)) ? $contacts : [];
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
        <h1 class="title">Contacts</h1>
        <p class="subtitle">Contact directory</p>
    </div>
    <div style="display:flex; gap: 10px;">
        <a href="/clients" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM5 21a7 7 0 0 1 14 0M18.5 12.5h4M20.5 10.5v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Clients
        </a>
        <button type="button" class="btn btn-primary" data-open-modal="#create-contact-modal">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            New Contact
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
        <?php if ($contacts === []): ?>
            <p class="muted">No contact(s) found.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th class="center">No. of linked clients</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($contact['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($contact['surname'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($contact['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="center"><?= htmlspecialchars((string) ($contact['clients_count'] ?? 0), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a class="btn btn-secondary btn-open" href="/contacts/<?= htmlspecialchars((string) ($contact['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
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

<div id="create-contact-modal" class="modal-overlay<?= $openCreateModal ? ' open' : '' ?>" data-modal-overlay>
    <div class="glass modal">
        <h2 style="margin-top: 0;">Create Contact</h2>
        <p class="subtitle" style="margin: 6px 0 18px;">All fields are required.</p>
        <form action="/contacts" method="post">
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="<?= htmlspecialchars((string) ($old['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" maxlength="255" required>
            </div>
            <div class="field">
                <label for="surname">Surname</label>
                <input id="surname" name="surname" type="text" value="<?= htmlspecialchars((string) ($old['surname'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" maxlength="255" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?= htmlspecialchars((string) ($old['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" maxlength="255" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-close-modal="#create-contact-modal">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12l5 5L20 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
