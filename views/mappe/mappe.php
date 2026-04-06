<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii;
use yii\bootstrap\Modal;
use app\assets\MapAsset;

?>
<script type="text/javascript">
var MAP_PATH = "<?php echo Yii::$app->request->baseUrl; ?>/views/";
var GOOGLE_MAPS_KEY = "<?php echo Yii::$app->params['googleMapsKey'] ?? ''; ?>";
var CTR2020_BASE_URL = "<?php echo Yii::$app->request->baseUrl; ?>/ctr2020geojson";
var MINOSX_PROXY_URL = "<?php echo \yii\helpers\Url::to(['mappe/minosx-lamps']); ?>";
</script>

<?php
    MapAsset::register($this);
?>
<!-- Make sure you put this AFTER Leaflet's CSS -->
 <!-- <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script> -->


<script src="https://unpkg.com/geojson-vt@3.2.0/geojson-vt.js"></script>
<script src="mappe/b542/ctrpunti_quota_0.js"></script>
<script src="mappe/b542/CTRCURVEDILIVELLOESCARPATE_1.js"></script>
<script src="mappe/b542/CTRIDROGRAFIA_E_OP_IDRPUNTI_2.js"></script>
<script src="mappe/b542/CTRIDROGRAFIA_E_OP_IDRPOLIGONI_3.js"></script>
<script src="mappe/b542/CTRIDROGRAFIA_E_OP_IDRLINEE_4.js"></script>
<script src="mappe/b542/CTREDIFICIPOLIGONI_5.js"></script>
<script src="mappe/b542/CTREDIFICILINEE_6.js"></script> 
<!--        <script src="mappe/b542/foglio02_Fabbricati.js"></script> 
        <script src="mappe/b542/foglio02_Particelle.js"></script> -->
        



<?php
// Mappe Campoli del Monte Taburno - B542
//use app\assets\MapAsset;

//$this->registerJsFile('mappe/b542/mappa.js');

//$this->registerJsFile('mappe/b542/layerstyle.js');
// Importo e registro mappe 
$this->registerJsFile('mappe/b542/prgjson.js');
$this->registerJsFile('mappe/b542/ptpjson.js');
$this->registerJsFile('mappe/b542/borghiagricoli.js');
$this->registerJsFile('mappe/b542/vincoloidrog.js');
$this->registerJsFile('mappe/b542/pianofrane.js');
$this->registerJsFile('mappe/b542/perimetro_comunale.js');
// $this->registerJsFile('mappe/b542/foglio01_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio01_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio02_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio02_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio03_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio03_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio04_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio04_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio05_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio05_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio06_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio06_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio07_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio07_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio08_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio08_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio09_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio09_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio10_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio10_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio11_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio11_Particelle.js');
// $this->registerJsFile('mappe/b542/foglio12_Fabbricati.js');
// $this->registerJsFile('mappe/b542/foglio12_Particelle.js');
$this->registerJsFile('mappe/b542/acquedotto-grieci.js');
// $this->registerJsFile('mappe/b542/foglio12_Particelle.js');

$this->registerJsFile('mappe/js/ptp_style.js');
$this->registerJsFile('mappe/js/ctr2020.js');
$this->registerJsFile('mappe/js/minosx.js');
//$this->registerJsFile('mappe/b542/layers.js');
//$this->registerJsFile('mappe/b542/stili.js');
//$this->registerJsFile('mappe/b542/cerca_particelle.js');
//$this->registerJsFile('mappe/b542/popup.js');
//$this->registerJsFile('mappe/b542/controlli.js');
//$this->registerJsFile('mappe/b542/pratiche_edilizie.js');
//$this->registerJsFile('mappe/b542/scheda_urbanistica.js');
// $this->registerJsFile('js/catastali_vettoriali.js');
// $this->registerJsFile('js/leaflet.pattern.js');
// $this->registerJsFile('js/multi-style-layer.js');
// //$this->registerJsFile('js/prgstyle.js');
// // MAPPE CATASTALI SERVIZIO WMS Agenzia Entrate
// $this->registerJsFile('js/wms.map.catasto.js');

// $this->registerJsFile('mappe/b542/function_collection.js');
// carico le varie parti js in ordine




// CSS Style
// $this->registerCssFile('mappe/b542/catastostyle.css');
// $this->registerCssFile('mappe/b542/tree_control.css');

$this->registerJs("
    layer_wms();
    map = new L.map('mapid', {
            //crs: crs_25833,
			center: [41.1305,14.645538], // centro in 6706
            //center: [4553368,470246], // centro in EPSG 25833
            //center: [5031710,1630302], // centro in EPSG 3857
			zoom: 17,
			layers: [googleSat,catasto],
			contextmenu: true,
			contextmenuWidth: 140,
			contextmenuItems: [{
                    text: 'Coordinate Qui',
                callback: showCoordinates
                }, {
                text: 'Centra Qui',
                callback: centerMap
                }, '-', {
                text: 'Info Particella',
                callback: showInfoX
                },'-',{
                text: 'Info Urbanistiche',
                callback: showInfo
                },{
                    text: 'Scheda Urbanistica',
                callback: showInfoCatasto
                    
			}]
			});

    baselayers = {
        label: 'google Maps',
        children: [
            { label: 'Satellite', layer: googleSat },
            // { label: 'terrain', layer: googleTerrain },
            // { label: 'Hybrid', layer: googleHybrid },
            // { label: 'RoadMap', layer: googleRoadMap },
            { label: 'Nessuna', layer: L.gridLayer() },
            ]
        };

    opacitylayers = {
        'Opacità Catastale': catasto,
        //'Vector Catastale':shpfile,
        };
    var cartella =" . json_encode($cartellaMappe) . "; // cartella dove sono salvati i geojson catastali    
    console.log('cartella = ',cartella);  
    layers_def(map);
    layer_catastali(map,cartella);
    addMyControls(map,baselayers,overlaysTree);

    document.getElementsByClassName('leaflet-control-measure-toggle')[0]
        .innerHTML = '';
    document.getElementsByClassName('leaflet-control-measure-toggle')[0] 

    

", yii\web\View::POS_LOAD);



// MAPPE BASE google
//$this->registerJsFile('js/googlemap.js',['position' => \yii\web\View::POS_HEAD]);

// altro codice JS
array_walk_recursive($pratiche, function (&$item, $key) {
    $item = null === $item ? '' : $item;
});




?>
<style type="text/css">
/* Overlay di attesa durante la preparazione della stampa */
#print-waiting-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255,255,255,0.82);
    z-index: 99999;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    font-size: 1.1em;
    color: #333;
}
#print-waiting-overlay.active { display: flex; }

@media print {
    @page { size: A3 landscape; margin: 0; }

    /* Tutto nascosto eccetto la mappa */
    body * { visibility: hidden !important; }

    #mapid,
    #mapid * { visibility: visible !important; }

    #mapid {
        position: fixed !important;
        top: 0 !important; left: 0 !important;
        /* Il container è già ridimensionato a 1587×1122px via JS prima di stampare */
        width:  1587px !important;
        height: 1122px !important;
    }

    /* Controlli nascosti — selettore più specifico di #mapid * per vincere */
    #mapid .leaflet-control-container,
    #mapid .leaflet-control-container * { visibility: hidden !important; }

    /* Scala bar visibile */
    #mapid .leaflet-control-scale,
    #mapid .leaflet-control-scale * { visibility: visible !important; }

    #print-waiting-overlay { display: none !important; }

    /* Etichetta scala visibile solo in stampa */
    #print-scale-label {
        display: block !important;
        visibility: visible !important;
        position: fixed !important;
        bottom: 8mm !important;
        left: 8mm !important;
        font-size: 13pt !important;
        font-weight: bold !important;
        color: red !important;
        z-index: 99999 !important;
        background: transparent !important;
    }
}

/*        body { font-family:Arial, Helvetica, Sans-Serif; font-size:0.8em;}
        #tblvisurau { border-collapse:collapse;}
        #tblvisurau h4 { margin:0px; padding:0px;}
        #tblvisurau img { float:right;}
        #tblvisurau ul { margin:10px 0 10px 40px; padding:0px;}
        #tblvisurau th { background:#7CB8E2 url(header_bkg.png) repeat-x scroll center left; color:#fff; padding:7px 15px; text-align:left;}
        #tblvisurau td { background:#C7DDEE none repeat-x scroll center left; color:#000; padding:7px 15px; }
        #tblvisurau tr.odd td { background:#fff url(row_bkg.png) repeat-x scroll center left; cursor:pointer; }
        #tblvisurau div.arrow { background:transparent url(arrows.png) no-repeat scroll 0px -16px; width:16px; height:16px; display:block;}
        #tblvisurau div.up { background-position:0px 0px;}*/
        #tblvisurau tr.DatiCensuari { background: #f8f9f9;cursor:pointer;}
        #tblvisurau tr.Intestati { background:white}
    </style>

<!-- Modal selezione scala di stampa -->
<div class="modal fade" id="modal-print" tabindex="-1" role="dialog" aria-labelledby="modalPrintTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPrintTitle"><i class="fas fa-print mr-2"></i>Stampa mappa A3</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="print-scale-select">Scala di stampa</label>
                    <select id="print-scale-select" class="form-control">
                        <option value="500">1 : 500</option>
                        <option value="1000">1 : 1.000</option>
                        <option value="2000" selected>1 : 2.000</option>
                        <option value="5000">1 : 5.000</option>
                        <option value="10000">1 : 10.000</option>
                        <option value="25000">1 : 25.000</option>
                    </select>
                </div>
                <small class="text-muted">La mappa verrà centrata sulla vista corrente alla scala selezionata.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="btn-do-print">
                    <i class="fas fa-print mr-1"></i>Stampa
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Etichetta scala (nascosta a schermo, visibile solo in stampa) -->
<div id="print-scale-label" style="display:none;"></div>

<!-- Overlay attesa caricamento tile -->
<div id="print-waiting-overlay">
    <div><i class="fas fa-spinner fa-spin fa-2x mb-2"></i></div>
    <div>Preparazione stampa in corso&hellip;</div>
</div>

<!--<div style="height:100vh;margin-top:0">-->
    <div id="mapid" style="width:100%;height:91vh;z-index:0;">

    
    
    
    </div>
    
        <div class="modal" tabindex="-1" role="dialog" id="modal-terreni" aria-labelledby="modal2Title">
           <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                 <div class="modal-header">
                    <h2 class="modal-title h5 " id="modal2Title">Informazioni Particella</h2>
                    <button type="button" id="btnclose" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                 </div>
                 <div class="modal-body">
                    <table id="tblvisura" class="w-auto" style="border: 1px solid #ddd !important;">
                        <tr>
                            <td valign="middle" colspan="2" style="border: 1px solid #ddd !important;vertical-align: middle;"><p>Catasto Terreni</p></td>
                            <td colspan="5" style="border: 1px solid #ddd !important;" id="idcomune"></td>
                        </tr>
                        <tr>
                            <td width="4%" style="border: 1px solid #ddd !important;text-align: center">foglio</td>
                            <td width="10%" style="border: 1px solid #ddd !important;text-align: center">particella</td>
                            <td width="10%" style="border: 1px solid #ddd !important;text-align: center">Qualità</td>
                            <td width="10%" style="border: 1px solid #ddd !important;text-align: center">Classe</td>
                            <td width="10%" style="border: 1px solid #ddd !important;text-align: center">Superficie mq</td>
                            <td width="10%" style="border: 1px solid #ddd !important;text-align: center">Reddito Dom</td>
                            <td width="10%" style="border: 1px solid #ddd !important;text-align: center">Reddito Agr</td>

                    </tr>
                    <tr>
                            <td id="idfoglio"  style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idparticella" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idqualita" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idclasse" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idsuperficie" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idredditodom" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idredditoagr" style="border: 1px solid #ddd !important;text-align: center"></td>
                    </tr>
                    <tr>
                        <td colspan="7" >INTESTATI</td>
                    </tr>
                    <tr>
                            <td style="border: 1px solid #ddd !important;">N.</td>
                            <td fontSize="10px" colspan="3" style="border: 1px solid #ddd !important;">DATI ANAGRAFICI</td>
                            <td fontSize="10px" style="border: 1px solid #ddd !important;">CODICE FISCALE</td>
                            <td colspan="2" style="border: 1px solid #ddd !important;">DIRITTI E ONERI REALI</td>
                        
                    </tr>
                    
                    </table>
                 </div>
                 <div class="modal-footer">
<!--                    <button class="btn btn-primary btn-sm" type="button">Ok</button>-->
                 </div>
              </div>
           </div>
        </div>


        <div class="modal" tabindex="-1" role="dialog" id="modal-fabbricati" aria-labelledby="modal2Title">
           <div class="modal-dialog modal-xl" role="document" >
              <div class="modal-content">
                 <div class="modal-header">
                    <h2 class="modal-title h5 " id="modal2Titleu">Informazioni Fabbricato - Catasto Fabbricati</h2>
                    <button type="button" id="btnclosef" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                 </div>
                 <div class="modal-body">
                    <table id="tblvisurau" class="w-auto table-hover table-expandable table-striped" style="border: 1px solid #ddd !important;">
<!--                        <tr>
                            <td valign="middle" colspan="2" style="border: 1px solid #ddd !important;vertical-align: middle;"><p>Catasto Fabbricati</p></td>
                            <td colspan="6" style="border: 1px solid #ddd !important;" id="idcomuneu"></td>
                        </tr>-->
                        <tr>
                            <td width="4%" style="border: 1px solid #ddd !important;text-align: center">foglio</td>
                            <td width="5%" style="border: 1px solid #ddd !important;text-align: center">particella</td>
                            <td width="4%" style="border: 1px solid #ddd !important;text-align: center">Sub</td>
                            <td width="5%" style="border: 1px solid #ddd !important;text-align: center">Categoria</td>
                            <td width="5%" style="border: 1px solid #ddd !important;text-align: center">Classe</td>
                            <td width="7%" style="border: 1px solid #ddd !important;text-align: center">Consistenza</td>
                            <td width="9%" style="border: 1px solid #ddd !important;text-align: center">Superficie</td>
                            <td width="7%" style="border: 1px solid #ddd !important;text-align: center">Rendita</td>
                            <td width="15%" style="border: 1px solid #ddd !important;text-align: center">Indirizzo</td>
                            <td width="5%" style="border: 1px solid #ddd !important;text-align: center">Piano</td>

                        </tr>
<!--                    <tr>
                            <td id="idfogliou"  style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idparticellau" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idsub" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idcategoria" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idclasseu" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idconsistenza" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idrendita" style="border: 1px solid #ddd !important;text-align: center"></td>
                            <td id="idsuperficie" style="border: 1px solid #ddd !important;text-align: center"></td>
                    </tr>-->
<!--                    <tr>
                        <td colspan="7" >INTESTATI</td>
                    </tr>
                    <tr>
                            <td style="border: 1px solid #ddd !important;">N.</td>
                            <td fontSize="10px" colspan="3" style="border: 1px solid #ddd !important;">DATI ANAGRAFICI</td>
                            <td fontSize="10px" style="border: 1px solid #ddd !important;">CODICE FISCALE</td>
                            <td colspan="3" style="border: 1px solid #ddd !important;">DIRITTI E ONERI REALI</td>
                        
                    </tr>-->
                    
                    </table>
                 </div>
                 <div class="modal-footer">
<!--                    <button class="btn btn-primary btn-sm" type="button">Ok</button>-->
                 </div>
              </div>
           </div>
        </div>

<!--        <div style="margin-left:5px;margin-top:5px;width:20%; height:10% display: inline-block;"> -->
        <?php 
    $form = ActiveForm::begin([
        'action' => [''],
        'id' => 'urb-form',
        'options' => [
            'class' => 'particelle-form'
        ]
    ]); 
    ?>
        <div style="margin-left:15px;margin-top:5px!important;display: inline-block;">    
            <?php // 'template' => '{label}{input}{error}{hint}',?>
        <?= $form->field($particella, 'foglio',['options' => ['class' => 'form-group form-inline'],])
       ->textInput(['style' => 'width: 40px; height:30px; margin-left: 5px;margin-top: 5px;']);
        ?>
        </div>
        <div style="margin-left:5px;margin-top:5px;display: inline-block;">    
        <?= $form->field($particella, 'particella',[
        'options' => ['class' => 'form-group form-inline'],])
        ->textInput(['style' => 'width: 60px; height:30px; margin-left: 5px;margin-top: 5px;']);
        ?>
        </div>
        <div style="margin-left:15px;margin-top:5px;display: inline-block;"> 
        <?php 
        //Html::a('<span class="fas fa-search"></span>', Url::toRoute(['edilizia/allegati','idpratica'=>$model->edilizia_id]),
//                      [  
//                         'title' => 'Gestione',
//                          
//                         //'data-confirm' => "Sei sicuro di volere eliminare questa istanza?",
//                         //'data-method' => 'post',
//                         //'data-pjax' => 0
//                      ]);   ?>
         <?php 
         // BUTTON PER CERCARE E ZOMMARE SULLA PARTICELLA SPECIFICATA
         //Html::submitButton('<i class="fas fa-search"></i>', ['class' => "btn btn-success _cercap",'style' => 'margin-top: 5px;height:30px;','id' => 'btnCerca']);
         //echo Html::submitButton(Icon::show('search'), ['class' => 'btn btn-primary _cercap btn', 'name' => 'action', 'value' => 'save']);
         echo Html::a('<i class="fas fa-search fa-sm" ></i>',[''], ['title'=>'Zoom alla particella indicata', 
             'class'=>'btn btn-primary _cercap','style' => 'margin-top: 2px;width:30px;height:30px;padding-left:7px!important;padding-top:0!important','id' => 'btnCerca',
             ])
     
         ?>
         <?php 
         // BUTTON PER STAMPARE LA SCHEDA URBANISTICA DELLA PARTICELLA
         //Html::submitButton('<i class="fas fa-search"></i>', ['class' => "btn btn-success _cercap",'style' => 'margin-top: 5px;height:30px;','id' => 'btnCerca']);
         //echo Html::submitButton(Icon::show('search'), ['class' => 'btn btn-primary _cercap btn', 'name' => 'action', 'value' => 'save']);
         echo Html::a('<i class="fas fa-file-pdf fa-sm" ></i>',$url=false, ['title'=>'Scheda urbanistica della particella indicata', 
             'class'=>'btn btn-success _surb','style' => 'margin-top: 2px;margin-left:4px;width:30px;height:30px;padding-left:8px!important;padding-top:0!important','id' => 'btnUrb',
             ])
     
         ?>
         </div>

    <?php ActiveForm::end(); ?>




