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
$selectedClientId = (int) ($_POST['client_id'] ?? 0);
?>

<div class="glass header">
    <div>
        <h1 class="title">Contact Details</h1>
        <p class="subtitle"><?= htmlspecialchars((string) (($contact['surname'] ?? '') . ' ' . ($contact['name'] ?? '')), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="/contacts" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Back to Contacts
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
            data-tab-group="contact-show"
            data-tab-target="#tab-general"
        >
            General
        </a>
        <a
            href="#"
            class="tab-button <?= $activeTab === 'clients' ? 'active' : '' ?>"
            data-tab-button
            data-tab-group="contact-show"
            data-tab-target="#tab-clients"
        >
            Client(s)
        </a>
    </div>
</div>

<section id="tab-general" class="glass card tab-panel <?= $activeTab === 'general' ? 'active' : '' ?>" data-tab-panel data-tab-group="contact-show">
    <div class="kv">
        <div class="field">
            <label for="contact-name">Name</label>
            <input id="contact-name" type="text" readonly value="<?= htmlspecialchars((string) ($contact['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="field">
            <label for="contact-surname">Surname</label>
            <input id="contact-surname" type="text" readonly value="<?= htmlspecialchars((string) ($contact['surname'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="field">
            <label for="contact-email">Email</label>
            <input id="contact-email" type="text" readonly value="<?= htmlspecialchars((string) ($contact['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>
 </section>

<section id="tab-clients" class="glass card tab-panel <?= $activeTab === 'clients' ? 'active' : '' ?>" data-tab-panel data-tab-group="contact-show">
    <?php if ($availableClients !== []): ?>
        <form action="/contacts/<?= htmlspecialchars((string) $contactId, ENT_QUOTES, 'UTF-8') ?>/clients" method="post" class="link-row">
            <div class="field">
                <label for="client_id">Link client</label>
                <select id="client_id" name="client_id" required>
                    <?php foreach ($availableClients as $client): ?>
                        <?php $isSelected = $selectedClientId > 0 && $selectedClientId === (int) ($client['id'] ?? 0); ?>
                        <option value="<?= htmlspecialchars((string) ($client['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"<?= $isSelected ? ' selected' : '' ?>>
                            <?= htmlspecialchars((string) (($client['name'] ?? '') . ' - ' . ($client['client_code'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
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
        <?php if ($linkedClients === []): ?>
            <p class="muted">No contact(s) found.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Client name</th>
                    <th>Client code</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($linkedClients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($client['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($client['client_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <form action="/contacts/<?= htmlspecialchars((string) $contactId, ENT_QUOTES, 'UTF-8') ?>/clients/<?= htmlspecialchars((string) ($client['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>/unlink" method="post" class="inline-form">
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
