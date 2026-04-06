<?php
/** @var \app\models\File $model */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'SingleFile';

$modelName = explode('.', $model->Name)[0];
$fileExtension = strtolower(pathinfo($model->Name, PATHINFO_EXTENSION));
?>

<head>
    <script src="js/jquery-3.5.0.min.js"></script>
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
    <script src="js/loadhtml.js"></script>
</head>
<style>
    body{
        margin: 0; /* Remove default margin */
    }
    iframe{      
        display: block;  /* iframes are inline by default */   
        height: 94vh;  /* Set height to 100% of the viewport height */   
        width: 95vw;  /* Set width to 100% of the viewport width */     
        border: none; /* Remove default border */
        background: lightyellow; /* Just for styling */
    }
</style>
<?php if ($fileExtension === 'ifc'): ?>
    <?php $this->context->layout = false; // disattiva layout Yii2 ?>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Bay Bridge</title>

        <link rel="stylesheet"
              href="<?= Url::to('@web/Viewer/lib/fontawesome-free-5.11.2-web/css/all.min.css') ?>"
              type="text/css" />
        <link rel="stylesheet" href="<?= Url::to('@web/Viewer/css/xeokit-bim-viewer.css') ?>" type="text/css" />
        <link href="<?= Url::to('@web/Viewer/css/Xeo.css') ?>" rel="stylesheet" />
        <link href="<?= Url::to('@web/Viewer/css/SideBar.css') ?>" rel="stylesheet" />
    </head>

    <div id="main">
        <div id="mySidebar" class="sidebar">
            <div class="topnav">
                <p>Projects</p>
            </div>
            <div id="treeViewContainer"></div>
        </div>

        <div id="myToolbar">
            <div class="xeokit-toolbar">
                <div class="xeokit-btn-group">
                    <!-- TreeViewer -->
                    <button id="TreeToggle"
                            class="explorer_toggle_label xeokit-btn fas fa-2x fa-sitemap"
                            data-tippy-content="Toggle explorer panel"></button>

                    <!-- Reset button -->
                    <button type="button"
                            class="xeokit-reset xeokit-btn fa fa-home fa-2x"
                            title="reset alla Vista Iniziale"
                            data-tippy-content="Reset view"
                            onclick="ResetViewer()"></button>

                    <!-- Perspective/Ortho Mode -->
                    <button type="button" id="toggle3d"
                            title="cambia Vista 2D/3D"
                            class="xeokit-threeD xeokit-btn fa fa-cube fa-2x"
                            data-tippy-content="Toggle 2D/3D"
                            onclick="SetCamera()"></button>
                </div>

                <!-- Tools -->
                <div class="xeokit-btn-group" role="group">
                    <button type="button"
                            id="sectionbtn"
                            title="Abilità Sezioni del modello"
                            class="xeokit-section xeokit-btn fa fa-cut fa-2x"
                            data-tippy-content="Slice objects">
                    </button>
                    <button type="button"
                            id="Query"
                            title="Ricerca Oggetti"
                            class="xeokit-query fa fa-info-circle fa-2x xeokit-btn"
                            data-tippy-content="Query objects">
                    </button>

                    <?php if ($model->FolderId === null): ?>
                        <a href="<?= Url::to(['folder/index', 'projectId' => $model->ProjectId]) ?>"
                           class="xeokit-section xeokit-btn fa fa-arrow-left fa-2x"
                           style="text-decoration:none"></a>
                    <?php else: ?>
                        <a href="<?= Url::to(['folder/index', 'projectId' => $model->ProjectId,'id' => $model->FolderId]) ?>"
                           class="xeokit-section xeokit-btn fa fa-arrow-left fa-2x"
                           style="text-decoration:none"></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <canvas id="myCanvas"></canvas>
        <canvas id="myNavCubeCanvas"></canvas>
        <canvas id="mySectionPlanesOverviewCanvas"></canvas>
    </div>

    <script src="<?= Url::to('@web/Viewer/scripts/Viewer.js') ?>" type="module"></script>
    <script src="<?= Url::to('@web/Viewer/scripts/TreeView.js') ?>" type="module"></script>
    <script src="<?= Url::to('@web/Viewer/scripts/NavCube.js') ?>" type="module"></script>
    <script src="<?= Url::to('@web/Viewer/scripts/SectionPlanes.js') ?>" type="module"></script>

    <script type="module">
        import { WebIFCLoaderPlugin } from "<?= Url::to('@web/Viewer/dist/xeokit-sdk.es.js') ?>";
        const webIFCLoader = new WebIFCLoaderPlugin(viewer, {
            wasmPath: "<?= Url::to('@web/Viewer/dist/') ?>",
        });

        const model1 = webIFCLoader.load({
            id: 1,
            src: "<?= Url::to($model->Path .'/'.$model->Name) ?>",
            loadMetadata: true,
            edges: false,
        });

        window.loaderifc = webIFCLoader;
    </script>
<?php elseif ($fileExtension === 'xls' || $fileExtension === 'xlsx'): ?>
        <div class="excel-viewer-container">
            <h2>Viewer Excel (PhpSpreadsheet)</h2>
                <!-- Mostra il contenuto Excel convertito in HTML -->
            <div style="overflow:auto; max-height:500px; border:1px solid #ccc; padding:10px;">
                <?= $htmlOutput ?>
            </div>
        </div>     
   
    <div id="viewer">
        <p class="no-sheet">
            No sheet selected. Please select a sheet.
        </p>
    </div>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script src="Viewer/excel/script.js"></script>
<?php elseif ($fileExtension === 'doc' || $fileExtension === 'docx'): ?>
        <div class="excel-viewer-container">
            <h2>Documento: <?= $model->Name ?></h2>
                <!-- Mostra il contenuto Excel convertito in HTML -->
            <!-- <div style="overflow:auto; max-height:500px; border:1px solid #ccc; padding:10px;"> -->
                 <!-- <div w3-include-html="<?= $htmlOutput; ?>"></div>  -->
            <div>
                <iframe src="./files/docword111222.html"></iframe>
                    
                <!-- <iframe src='"https://docs.google.com/gview?url=http://192.168.1.225/".<?= $htmlOutput ?>. "&embedded=true"'></iframe> -->
            </div>
        </div>     
   
    
<?php else: ?>
    <?php if ($model->FolderId === null): ?>
        <a href="<?= Url::to(['folder/index', 'projectId' => $model->ProjectId]) ?>"
           class="btn btn-outline-dark" style="position:relative; top:-10px;">
            <i class="fa-solid fa-circle-left"></i> &nbsp; Back
        </a>
    <?php else: ?>
        <a href="<?= Url::to(['folder/index', 'projectId' => $model->ProjectId,'id' => $model->FolderId]) ?>"
           class="btn btn-outline-dark" style="position:relative; top:-10px;">
            <i class="fa-solid fa-circle-left"></i> &nbsp; Back
        </a>
    <?php endif; ?>

    <embed id="embPDF"
           src="<?= Url::to($model->path) ?>"
           style="width:100%; height:1200px;" />
<?php endif; ?>
