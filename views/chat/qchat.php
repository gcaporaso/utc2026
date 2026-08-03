<?php
/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'AI UTC-BIM — Archivio';
$this->registerJsFile('js/aiutils.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1>Interrogazioni AI <small>powered by Ollama — locale</small></h1>
  </section>

  <section class="content">
    <div class="row">

      <!-- ===== CHAT DB ===== -->
      <div class="col-md-8 offset-md-2">
        <div class="card card-primary direct-chat direct-chat-primary" style="min-height:600px">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-robot"></i> AI-UTC-BIM — Archivio</h3>
            <div class="card-tools">
              <span id="ollama-status" class="badge badge-secondary" title="Stato Ollama">
                <i class="fas fa-circle"></i> Ollama
              </span>
            </div>
          </div>

          <div class="card-body p-0">
            <div id="chatbody" class="direct-chat-messages" style="height:520px;overflow-y:auto;padding:10px">
              <div class="direct-chat-msg">
                <div class="direct-chat-infos clearfix">
                  <span class="direct-chat-name float-left">AI-UTC-BIM</span>
                  <span class="direct-chat-timestamp float-right"><?= date('d/m/Y H:i') ?></span>
                </div>
                <img class="direct-chat-img" src="<?= Yii::$app->request->baseUrl ?>/img/AI-uman.png" alt="AI">
                <div class="direct-chat-text">
                  Buongiorno! Sono il tuo assistente per l'archivio UTC-BIM.<br>
                  Puoi chiedermi informazioni su pratiche edilizie, sismiche, paesaggistiche, CDU,
                  commissioni, tecnici, imprese e molto altro.<br>
                  <small class="text-muted">Esempi: "Quante pratiche del 2024?", "CDU rilasciati quest'anno", "Pratiche sismiche senza collaudo"</small>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="input-group">
              <input id="msguser" type="text" placeholder="Scrivi la tua domanda…" class="form-control" autocomplete="off">
              <span class="input-group-append">
                <button type="button" onclick="airequest()" class="btn btn-primary">
                  <i class="fas fa-paper-plane"></i> Invia
                </button>
              </span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php
$js = <<<JS
// Verifica stato Ollama
$.ajax({
    url: 'index.php?r=chat/status',
    type: 'GET',
    timeout: 3000,
}).done(function(r) {
    var badge = document.getElementById('ollama-status');
    if (r && r.ok) {
        badge.className = 'badge badge-success';
        badge.innerHTML = '<i class="fas fa-circle"></i> Ollama OK';
    } else {
        badge.className = 'badge badge-warning';
        badge.innerHTML = '<i class="fas fa-exclamation-circle"></i> Ollama non risponde';
    }
}).fail(function() {
    var badge = document.getElementById('ollama-status');
    badge.className = 'badge badge-danger';
    badge.innerHTML = '<i class="fas fa-times-circle"></i> Ollama offline';
});

document.getElementById('msguser').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') airequest();
});
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>
