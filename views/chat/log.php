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
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
            <tr class="<?= $r['response_ok'] ? '' : 'table-danger' ?>">
              <td><?= $r['id'] ?></td>
              <td><small><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></small></td>
              <td style="max-width:250px;white-space:normal"><?= htmlspecialchars($r['user_query']) ?></td>
              <td><?= $typeIcon[$r['action_type']] ?? htmlspecialchars($r['action_type']) ?></td>
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
              <td><?= $feedbackLabel($r['feedback'] === null ? null : (int)$r['feedback']) ?></td>
              <td style="max-width:150px"><small><?= htmlspecialchars($r['feedback_note'] ?? '') ?></small></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($rows)): ?>
            <tr><td colspan="10" class="text-center text-muted py-4">Nessuna interazione registrata.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </section>
</div>
