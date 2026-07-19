<?php
/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'AI UTC-BIM';
$this->registerJsFile('js/aiutils.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['position' => \yii\web\View::POS_HEAD]);
?>

<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1>Interrogazioni AI <small>powered by Ollama — locale</small></h1>
  </section>

  <section class="content">
    <div class="row">

      <!-- ===== CHAT ===== -->
      <div class="col-md-5">
        <div class="card card-primary direct-chat direct-chat-primary" style="min-height:600px">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-robot"></i> AI-UTC-BIM</h3>
            <div class="card-tools">
              <span id="ollama-status" class="badge badge-secondary" title="Stato Ollama">
                <i class="fas fa-circle"></i> Ollama
              </span>
            </div>
          </div>

          <div class="card-body" style="height:480px;overflow-y:auto">
            <div id="chatbody" class="direct-chat-messages" style="height:100%;overflow:visible">
              <div class="direct-chat-msg">
                <div class="direct-chat-infos clearfix">
                  <span class="direct-chat-name float-left">AI-UTC-BIM</span>
                  <span class="direct-chat-timestamp float-right"><?= date('d/m/Y H:i') ?></span>
                </div>
                <img class="direct-chat-img" src="img/user2-160x160.jpg" alt="AI">
                <div class="direct-chat-text">
                  Buongiorno! Sono il tuo assistente per l'archivio UTC-BIM.<br>
                  Puoi chiedermi informazioni su pratiche edilizie, sismiche, paesaggistiche, CDU, commissioni,
                  e puoi anche chiedermi di evidenziare elementi sulla mappa.<br>
                  <small class="text-muted">Esempi: "Quante pratiche del 2024?", "Evidenzia foglio 5 sulla mappa"</small>
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

        <!-- Suggerimenti rapidi -->
        <div class="card card-outline card-secondary mt-2">
          <div class="card-header py-2"><h3 class="card-title">Domande rapide</h3></div>
          <div class="card-body py-2">
            <?php
            $suggerimenti = [
                'Quante pratiche edilizie nel 2024?',
                'Pratiche sismiche senza collaudo',
                'CDU rilasciati quest\'anno',
                'Rate di oneri scadute e non pagate',
                'Prossima seduta di commissione',
                'Evidenzia foglio 5 sulla mappa',
                'Mostra il piano regolatore sulla mappa',
            ];
            foreach ($suggerimenti as $s): ?>
              <button class="btn btn-xs btn-outline-secondary mb-1"
                      onclick="document.getElementById('msguser').value=<?= json_encode($s) ?>;airequest()">
                <?= htmlspecialchars($s) ?>
              </button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- ===== MAPPA ===== -->
      <div class="col-md-7">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-map"></i> Mappa interattiva</h3>
            <div class="card-tools">
              <button class="btn btn-xs btn-tool" onclick="resetMap()" title="Rimuovi evidenziazioni">
                <i class="fas fa-times-circle"></i> Reset
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <div id="ai-map" style="height:600px;width:100%"></div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php
$mapCenter = json_encode([41.3869, 14.5847]); // Campoli del Monte Taburno
$geojsonUrl = Url::to(['chat/edilizia-geojson']);

$js = <<<JS
// --- Inizializzazione mappa Leaflet ---
var map = L.map('ai-map').setView({$mapCenter}, 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19,
}).addTo(map);

// Registra i layer GeoJSON già caricati nel namespace window (catasto, PRG, ecc.)
var knownLayers = {};
var layerNames = [
    'piano_regolatore_generale','vincoli_idrografici','piano_frane',
    'piano_territoriale_paesistico','perimetro_comunale','borghi_agricoli',
];
layerNames.forEach(function(n) {
    if (window[n]) knownLayers[n] = window[n];
});

// Fogli catastali
for (var i = 1; i <= 12; i++) {
    var fn = 'foglio' + String(i).padStart(2,'0') + '_Particelle';
    if (window[fn]) knownLayers[fn] = window[fn];
}

// Inizializza il modulo AI chat con la mappa
initAiChat(map, knownLayers);

// --- Verifica stato Ollama ---
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
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>
