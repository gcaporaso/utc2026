<?php
/* @var $this yii\web\View */
/* @var $backups array */
/* @var $restoreLog array */

use yii\helpers\Html;

$this->title = 'Backup Database';
?>

<!-- Modale conferma ripristino -->
<div class="modal fade" id="modal-restore" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Conferma ripristino</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Stai per ripristinare il database dal backup:</p>
                <p class="font-weight-bold" id="restore-filename"></p>
                <div class="alert alert-info mb-2">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Prima del ripristino verrà creato automaticamente un <strong>backup di sicurezza</strong>
                    del database corrente.
                </div>
                <p class="text-danger mb-0">
                    <i class="fas fa-warning mr-1"></i>
                    <strong>Attenzione:</strong> tutti i dati attuali verranno sostituiti con quelli del backup selezionato.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <?= Html::beginForm(['db-backup/restore'], 'post', ['id' => 'form-restore']) ?>
                    <input type="hidden" name="file" id="restore-file-input">
                    <?= Html::submitButton(
                        '<i class="fas fa-undo mr-1"></i>Ripristina',
                        ['class' => 'btn btn-warning', 'onclick' => 'this.disabled=true;this.form.submit();']
                    ) ?>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="fas fa-database mr-2"></i>Backup Database</h4>
        <?= Html::beginForm(['db-backup/create'], 'post', ['class' => 'd-inline']) ?>
            <?= Html::submitButton(
                '<i class="fas fa-download mr-1"></i>Crea nuovo backup',
                ['class' => 'btn btn-primary', 'onclick' => 'this.disabled=true;this.form.submit();']
            ) ?>
        <?= Html::endForm() ?>
    </div>

    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $messages): ?>
        <?php foreach ((array)$messages as $msg): ?>
            <div class="alert alert-<?= $type === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show">
                <?= $msg /* già sanitizzato lato controller */ ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <!-- Tabella backup -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Backup disponibili</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($backups)): ?>
                <p class="text-muted p-3 mb-0">Nessun backup presente.</p>
            <?php else: ?>
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>File</th>
                            <th>Data</th>
                            <th>Dimensione</th>
                            <th class="text-right">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($backups as $b): ?>
                        <tr>
                            <td>
                                <i class="fas fa-file-archive mr-1 text-secondary"></i>
                                <?= Html::encode($b['name']) ?>
                                <?php if (strpos($b['name'], '_pre_restore_') !== false): ?>
                                    <span class="badge badge-warning ml-1" title="Backup automatico pre-ripristino">sicurezza</span>
                                <?php endif; ?>
                            </td>
                            <td><?= Html::encode($b['date']) ?></td>
                            <td><?= Html::encode(number_format($b['size'] / 1048576, 2) . ' MB') ?></td>
                            <td class="text-right text-nowrap">
                                <!-- Ripristina -->
                                <button type="button" class="btn btn-sm btn-outline-warning mr-1"
                                        title="Ripristina database da questo backup"
                                        onclick="confirmRestore(<?= json_encode($b['name']) ?>)">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <!-- Scarica -->
                                <?= Html::a(
                                    '<i class="fas fa-download"></i>',
                                    ['db-backup/download', 'file' => $b['name']],
                                    ['class' => 'btn btn-sm btn-outline-primary mr-1', 'title' => 'Scarica']
                                ) ?>
                                <!-- Elimina -->
                                <?= Html::beginForm(['db-backup/delete', 'file' => $b['name']], 'post', ['class' => 'd-inline']) ?>
                                    <?= Html::submitButton(
                                        '<i class="fas fa-trash"></i>',
                                        [
                                            'class'   => 'btn btn-sm btn-outline-danger',
                                            'title'   => 'Elimina',
                                            'onclick' => "return confirm('Eliminare " . Html::encode(addslashes($b['name'])) . "?')",
                                        ]
                                    ) ?>
                                <?= Html::endForm() ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Cronologia ripristini -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-history mr-1"></i>Cronologia ripristini
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($restoreLog)): ?>
                <p class="text-muted p-3 mb-0">Nessun ripristino effettuato.</p>
            <?php else: ?>
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Data operazione</th>
                            <th>Ripristinato da</th>
                            <th>Backup di sicurezza</th>
                            <th>Utente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($restoreLog as $entry): ?>
                        <tr>
                            <td><?= Html::encode($entry['timestamp'] ?? '') ?></td>
                            <td><code><?= Html::encode($entry['restored_from'] ?? '') ?></code></td>
                            <td><code class="text-warning"><?= Html::encode($entry['safety_backup'] ?? '') ?></code></td>
                            <td><?= Html::encode($entry['user'] ?? '') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmRestore(filename) {
    document.getElementById('restore-filename').textContent = filename;
    document.getElementById('restore-file-input').value = filename;
    $('#modal-restore').modal('show');
}
</script>
