<?php
/** @var yii\web\View $this */
/** @var array $rows */
/** @var array $stats */

$this->title = 'AI Log — Analisi interazioni';

$feedbackLabel = function ($v) {
    if ($v === null) return '<span class="text-muted">—</span>';
    return $v ? '<span class="badge badge-success">👍</span>' : '<span class="badge badge-danger">👎</span>';
};

$typeIcon = [
    'sql'     => '<span class="badge badge-primary">SQL</span>',
    'map'     => '<span class="badge badge-info">Mappa</span>',
    'clarify' => '<span class="badge badge-warning">Clarify</span>',
    'error'   => '<span class="badge badge-danger">Errore</span>',
];
?>

<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1>AI Chat Log <small>Analisi interazioni per fine-tuning</small></h1>
  </section>

  <section class="content">

    <!-- Statistiche riassuntive -->
    <div class="row mb-3">
      <div class="col-md-2">
        <div class="small-box bg-info">
          <div class="inner"><h3><?= $stats['totale'] ?></h3><p>Interazioni totali</p></div>
          <div class="icon"><i class="fas fa-comments"></i></div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="small-box bg-success">
          <div class="inner"><h3><?= $stats['ok'] ?></h3><p>Risposte OK</p></div>
          <div class="icon"><i class="fas fa-check"></i></div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="small-box bg-danger">
          <div class="inner"><h3><?= $stats['errori'] ?></h3><p>Errori</p></div>
          <div class="icon"><i class="fas fa-times"></i></div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="small-box bg-success">
          <div class="inner"><h3><?= $stats['feedback_pos'] ?></h3><p>Feedback 👍</p></div>
          <div class="icon"><i class="fas fa-thumbs-up"></i></div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="small-box bg-warning">
          <div class="inner"><h3><?= $stats['feedback_neg'] ?></h3><p>Feedback 👎</p></div>
          <div class="icon"><i class="fas fa-thumbs-down"></i></div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="small-box bg-secondary">
          <div class="inner"><h3><?= $stats['avg_ms'] ?> ms</h3><p>Tempo medio</p></div>
          <div class="icon"><i class="fas fa-stopwatch"></i></div>
        </div>
      </div>
    </div>

    <!-- Filtri -->
    <div class="card card-outline card-secondary mb-3">
      <div class="card-body py-2">
        <form method="GET">
          <input type="hidden" name="r" value="chat/log">
          <div class="row align-items-center">
            <div class="col-md-2">
              <select name="type" class="form-control form-control-sm">
                <option value="">Tutti i tipi</option>
                <option value="sql"     <?= (Yii::$app->request->get('type') === 'sql')     ? 'selected' : '' ?>>SQL</option>
                <option value="map"     <?= (Yii::$app->request->get('type') === 'map')     ? 'selected' : '' ?>>Mappa</option>
                <option value="clarify" <?= (Yii::$app->request->get('type') === 'clarify') ? 'selected' : '' ?>>Clarify</option>
                <option value="error"   <?= (Yii::$app->request->get('type') === 'error')   ? 'selected' : '' ?>>Errori</option>
              </select>
            </div>
            <div class="col-md-2">
              <select name="feedback" class="form-control form-control-sm">
                <option value="">Tutto il feedback</option>
                <option value="neg"  <?= (Yii::$app->request->get('feedback') === 'neg')  ? 'selected' : '' ?>>Solo 👎 negativi</option>
                <option value="pos"  <?= (Yii::$app->request->get('feedback') === 'pos')  ? 'selected' : '' ?>>Solo 👍 positivi</option>
                <option value="none" <?= (Yii::$app->request->get('feedback') === 'none') ? 'selected' : '' ?>>Senza feedback</option>
              </select>
            </div>
            <div class="col-md-2">
              <input type="text" name="q" value="<?= htmlspecialchars(Yii::$app->request->get('q', '')) ?>"
                     placeholder="Cerca nella domanda…" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-sm btn-primary">Filtra</button>
              <a href="?r=chat/log" class="btn btn-sm btn-secondary">Reset</a>
            </div>
            <div class="col-md-4 text-right">
              <a href="?r=chat/log&export=jsonl<?= http_build_query(array_filter(Yii::$app->request->get())) ? '&' . http_build_query(array_filter(Yii::$app->request->get())) : '' ?>"
                 class="btn btn-sm btn-outline-dark">
                <i class="fas fa-download"></i> Esporta JSONL (per fine-tuning)
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabella log -->
    <div class="card">
      <div class="card-body p-0">
        <table class="table table-sm table-hover table-striped mb-0">
          <thead class="thead-light">
            <tr>
              <th>#</th>
              <th>Data/Ora</th>
              <th>Domanda</th>
              <th>Tipo</th>
              <th>SQL / Azione</th>
              <th>Righe</th>
              <th>ms</th>
              <th>Esito</th>
              <th>Feedback</th>
              <th>Note</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
            <tr class="<?= $r['response_ok'] ? '' : 'table-danger' ?>" id="log-row-<?= $r['id'] ?>">
              <td><?= $r['id'] ?></td>
              <td><small><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></small></td>
              <td style="max-width:250px;white-space:normal"><?= htmlspecialchars($r['user_query']) ?></td>
              <td class="cell-action-type"><?= $typeIcon[$r['action_type']] ?? htmlspecialchars($r['action_type']) ?></td>
              <td style="max-width:300px">
                <?php if ($r['sql_query']): ?>
                  <details><summary><small>Mostra SQL</small></summary>
                  <pre style="font-size:10px;white-space:pre-wrap"><?= htmlspecialchars($r['sql_query']) ?></pre></details>
                <?php elseif ($r['map_action']): ?>
                  <small><?= htmlspecialchars($r['map_action']) ?></small>
                <?php else: ?>—<?php endif; ?>
              </td>
              <td><?= $r['row_count'] ?? '—' ?></td>
              <td><small><?= $r['response_ms'] ?? '—' ?></small></td>
              <td><?= $r['response_ok'] ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' ?></td>
              <td class="cell-feedback"><?= $feedbackLabel($r['feedback'] === null ? null : (int)$r['feedback']) ?></td>
              <td style="max-width:180px" class="cell-note"><small><?= htmlspecialchars($r['feedback_note'] ?? '') ?></small></td>
              <td>
                <button class="btn btn-xs btn-outline-secondary btn-edit-log"
                        title="Modifica"
                        data-id="<?= $r['id'] ?>"
                        data-feedback="<?= $r['feedback'] === null ? '' : (int)$r['feedback'] ?>"
                        data-note="<?= htmlspecialchars($r['feedback_note'] ?? '') ?>"
                        data-type="<?= htmlspecialchars($r['action_type']) ?>">
                  <i class="fas fa-pencil-alt"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($rows)): ?>
            <tr><td colspan="11" class="text-center text-muted py-4">Nessuna interazione registrata.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </section>
</div>

<!-- Modal modifica record log -->
<div class="modal fade" id="modalEditLog" tabindex="-1" role="dialog" aria-labelledby="modalEditLogLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="modalEditLogLabel"><i class="fas fa-pencil-alt"></i> Modifica record #<span id="edit-log-id"></span></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-id">

        <div class="form-group">
          <label>Tipo azione</label>
          <select id="edit-action-type" class="form-control">
            <option value="sql">SQL</option>
            <option value="map">Mappa</option>
            <option value="clarify">Clarify</option>
            <option value="error">Errore</option>
          </select>
        </div>

        <div class="form-group">
          <label>Feedback</label>
          <select id="edit-feedback" class="form-control">
            <option value="">— nessuno —</option>
            <option value="1">👍 Positivo</option>
            <option value="0">👎 Negativo</option>
          </select>
        </div>

        <div class="form-group">
          <label>Note</label>
          <textarea id="edit-feedback-note" class="form-control" rows="4" placeholder="Note sul feedback o sull'interazione…"></textarea>
        </div>

        <div id="edit-log-alert" class="alert d-none"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
        <button type="button" class="btn btn-primary" id="btn-save-log">
          <i class="fas fa-save"></i> Salva
        </button>
      </div>
    </div>
  </div>
</div>

<?php
$baseUrl = Yii::$app->request->baseUrl;
$this->registerJs(<<<JS
$(function () {

    // Apri modal al click sul bottone matita
    $(document).on('click', '.btn-edit-log', function () {
        var btn = $(this);
        $('#edit-id').val(btn.data('id'));
        $('#edit-log-id').text(btn.data('id'));
        $('#edit-feedback').val(String(btn.data('feedback')));
        $('#edit-feedback-note').val(btn.data('note'));
        $('#edit-action-type').val(btn.data('type'));
        $('#edit-log-alert').addClass('d-none').removeClass('alert-success alert-danger').text('');
        $('#modalEditLog').modal('show');
    });

    // Salva via AJAX
    $('#btn-save-log').on('click', function () {
        var id   = $('#edit-id').val();
        var data = {
            id:            id,
            feedback:      $('#edit-feedback').val(),
            feedback_note: $('#edit-feedback-note').val(),
            action_type:   $('#edit-action-type').val(),
        };

        $('#btn-save-log').prop('disabled', true);

        $.ajax({
            url:      'index.php?r=chat/update-log',
            type:     'POST',
            dataType: 'json',
            data:     data,
        }).done(function (r) {
            if (r && r.ok) {
                // Aggiorna la riga in-place senza ricaricare la pagina
                var row = $('#log-row-' + id);

                // Badge feedback
                var fb = $('#edit-feedback').val();
                var fbHtml = fb === '1'  ? '<span class="badge badge-success">👍</span>'
                           : fb === '0'  ? '<span class="badge badge-danger">👎</span>'
                           :               '<span class="text-muted">—</span>';
                row.find('.cell-feedback').html(fbHtml);

                // Note
                row.find('.cell-note').html('<small>' + $('<span>').text($('#edit-feedback-note').val()).html() + '</small>');

                // Tipo azione
                var typeMap = {
                    sql:     '<span class="badge badge-primary">SQL</span>',
                    map:     '<span class="badge badge-info">Mappa</span>',
                    clarify: '<span class="badge badge-warning">Clarify</span>',
                    error:   '<span class="badge badge-danger">Errore</span>',
                };
                row.find('.cell-action-type').html(typeMap[$('#edit-action-type').val()] || $('#edit-action-type').val());

                // Aggiorna data-* del bottone per successivi edit
                row.find('.btn-edit-log')
                   .data('feedback', fb)
                   .data('note', $('#edit-feedback-note').val())
                   .data('type', $('#edit-action-type').val());

                $('#edit-log-alert').removeClass('d-none alert-danger').addClass('alert-success').text('Salvato correttamente.');
                setTimeout(function () { $('#modalEditLog').modal('hide'); }, 800);
            } else {
                $('#edit-log-alert').removeClass('d-none alert-success').addClass('alert-danger').text('Errore durante il salvataggio.');
            }
        }).fail(function () {
            $('#edit-log-alert').removeClass('d-none alert-success').addClass('alert-danger').text('Errore di rete.');
        }).always(function () {
            $('#btn-save-log').prop('disabled', false);
        });
    });

});
JS
); ?>
