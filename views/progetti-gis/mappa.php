<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\MapAsset;
MapAsset::register($this);
$geoJsonUrl = Url::to(['progetti-gis/geo-json', 'id' => $progetto->id]);
$this->title = 'Mappa — ' . $progetto->nome;
?>

<div id="mapid" style="width:100%;height:91vh;z-index:0;"></div>

<div style="position:fixed;bottom:0;left:0;right:0;background:#f4f4f4;border-top:1px solid #ddd;padding:4px 12px;z-index:1000;font-size:12px;">
    <b><?= Html::encode($progetto->nome) ?></b>
    <span class="ml-3 text-muted"><?= $progetto->getTipoLabel() ?></span>
    <?= Html::a('<i class="fas fa-list mr-1"></i>Dettaglio', ['view', 'id' => $progetto->id], ['class' => 'btn btn-xs btn-secondary ml-3']) ?>
    <span id="gis-cursor-coords" style="float:right;font-family:monospace;color:#555;">Lat: — Lng: —</span>
</div>

<?php
$this->registerJs("
    map = new L.map('mapid', {
        center: [41.1305, 14.6455],
        zoom: 14
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    map.on('mousemove', function(e) {
        document.getElementById('gis-cursor-coords').textContent =
            'Lat: ' + e.latlng.lat.toFixed(6) + '  Lng: ' + e.latlng.lng.toFixed(6);
    });

    // Carica il GeoJSON del progetto
    fetch(" . json_encode($geoJsonUrl) . ")
        .then(function(r){ return r.json(); })
        .then(function(data) {
            var layerColors = ['#1565C0','#E65100','#2E7D32','#6A1B9A','#B71C1C','#00838F','#546E7A'];
            var colorIdx = 0;
            var layerMap = {};

            // raggruppa le feature per layer
            (data.features || []).forEach(function(f) {
                var ln = (f.properties && f.properties._layer) || 'Layer';
                if (!layerMap[ln]) layerMap[ln] = [];
                layerMap[ln].push(f);
            });

            var overlays = {};
            Object.keys(layerMap).forEach(function(ln) {
                var color = layerColors[colorIdx++ % layerColors.length];
                var gl = L.geoJSON({type:'FeatureCollection', features: layerMap[ln]}, {
                    style: function() { return {color: color, weight: 2, fillOpacity: 0.35}; },
                    pointToLayer: function(f, ll) {
                        return L.circleMarker(ll, {radius: 6, color: color, fillColor: color, fillOpacity: 0.8});
                    },
                    onEachFeature: function(f, layer) {
                        var props = Object.assign({}, f.properties);
                        delete props._layer; delete props._tipo;
                        var html = '<b>' + ln + '</b><br>' +
                            Object.entries(props).map(function(kv){ return '<i>' + kv[0] + '</i>: ' + kv[1]; }).join('<br>');
                        layer.bindPopup(html);
                    }
                }).addTo(map);
                overlays[ln] = gl;
            });

            if (data.features && data.features.length > 0) {
                try { map.fitBounds(L.geoJSON(data).getBounds(), {padding: [20,20]}); } catch(e){}
            }

            if (Object.keys(overlays).length > 0) {
                L.control.layers(null, overlays, {collapsed: false}).addTo(map);
            }
        })
        .catch(function(e){ console.error('Errore caricamento GeoJSON:', e); });
", \yii\web\View::POS_LOAD);
?>
