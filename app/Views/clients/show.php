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
$selectedContactId = (int) ($_POST['contact_id'] ?? 0);
?>

<div class="glass header">
    <div>
        <h1 class="title">Client Details</h1>
        <p class="subtitle"><?= htmlspecialchars((string) ($client['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-secondary" data-open-modal="#edit-client-modal">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 20h4l10-10-4-4L4 16v4zM13 7l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Edit General
        </button>
        <a href="/clients" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Back to Clients
        </a>
    </div>
</div>

<?php if ($statusMessage !== null): ?>
    <p class="status flash-message"><?= htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<?php if ($errorMessage !== null): ?>
    <p class="error flash-message"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<div class="tabs-shell">
    <div class="tabs">
        <a
            href="#"
            class="tab-button <?= $activeTab === 'general' ? 'active' : '' ?>"
            data-tab-button
            data-tab-group="client-show"
            data-tab-target="#tab-general"
        >
            General
        </a>
        <a
            href="#"
            class="tab-button <?= $activeTab === 'contacts' ? 'active' : '' ?>"
            data-tab-button
            data-tab-group="client-show"
            data-tab-target="#tab-contacts"
        >
            Contact(s)
        </a>
    </div>
</div>

<section id="tab-general" class="glass card tab-panel <?= $activeTab === 'general' ? 'active' : '' ?>" data-tab-panel data-tab-group="client-show">
    <div class="kv">
        <div class="field">
            <label for="client-name">Name</label>
            <input id="client-name" type="text" readonly value="<?= htmlspecialchars((string) ($client['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="field">
            <label for="client-code">Client code</label>
            <input id="client-code" type="text" readonly value="<?= htmlspecialchars((string) ($client['client_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>
 </section>

<section id="tab-contacts" class="glass card tab-panel <?= $activeTab === 'contacts' ? 'active' : '' ?>" data-tab-panel data-tab-group="client-show">
    <?php if ($availableContacts !== []): ?>
        <form action="/clients/<?= htmlspecialchars((string) $clientId, ENT_QUOTES, 'UTF-8') ?>/contacts" method="post" class="link-row">
            <div class="field">
                <label for="contact_id">Link contact</label>
                <select id="contact_id" name="contact_id" required>
                    <?php foreach ($availableContacts as $contact): ?>
                        <?php $isSelected = $selectedContactId > 0 && $selectedContactId === (int) ($contact['id'] ?? 0); ?>
                        <option value="<?= htmlspecialchars((string) ($contact['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"<?= $isSelected ? ' selected' : '' ?>>
                            <?= htmlspecialchars((string) (($contact['surname'] ?? '') . ' ' . ($contact['name'] ?? '') . ' - ' . ($contact['email'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary btn-link-action">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10.5 13.5l3-3M8.2 15.8l-1.5 1.5a3 3 0 1 1-4.2-4.2L4 11.6a3 3 0 0 1 4.2 0M15.8 8.2l1.5-1.5a3 3 0 1 1 4.2 4.2L20 12.4a3 3 0 0 1-4.2 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Link
            </button>
        </form>
    <?php endif; ?>

    <div class="table-wrap">
        <?php if ($linkedContacts === []): ?>
            <p class="muted">No contacts found.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Contact full name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($linkedContacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) (($contact['surname'] ?? '') . ' ' . ($contact['name'] ?? '')), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($contact['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <form action="/clients/<?= htmlspecialchars((string) $clientId, ENT_QUOTES, 'UTF-8') ?>/contacts/<?= htmlspecialchars((string) ($contact['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>/unlink" method="post" class="inline-form">
                                <button type="submit" class="btn btn-danger btn-xs">
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 12h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                    Unlink
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<div id="edit-client-modal" class="modal-overlay" data-modal-overlay>
    <div class="glass modal">
        <h2 style="margin-top:0;">General Details</h2>
        <p class="subtitle" style="margin: 6px 0 16px;">Client code remains system-generated and readonly.</p>
        <div class="field">
            <label for="client-name-modal">Name</label>
            <input id="client-name-modal" type="text" readonly value="<?= htmlspecialchars((string) ($client['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="field">
            <label for="client-code-modal">Client code</label>
            <input id="client-code-modal" type="text" readonly value="<?= htmlspecialchars((string) ($client['client_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-primary" data-close-modal="#edit-client-modal">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6l-12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Close
            </button>
        </div>
    </div>
</div>
