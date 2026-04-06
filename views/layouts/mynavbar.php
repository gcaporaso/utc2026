<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    
    <ul class="navbar-nav">
        
        <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link"  href="index.php?r=project/index" role="button">
                <img src="/img/back-48_1.png" style="width:32px" />
                <!-- <i class="fas fa-search"></i> -->
            </a>
        </li>

    
        <li class="nav-item d-none d-sm-inline-block">
            <h1><?= $Name ?></h1>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">

            <a href="<?=\yii\helpers\Url::home()?>" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li> -->
      
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <!-- <a class="nav-link" href="index.php?r=folder/create&projectId=<?php // $pId ?>" role="button"> -->
                <a class="nav-link" id="btnNewFolder" href="#" role="button">
                <img src="/img/new-folder_2.png" title="crea una cartella" style="width:32px" />
                <!-- <i class="fas fa-search"></i> -->
            </a>
            <!-- <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div> -->
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item">
            <a id="input-700" class="nav-link" href="#" role="button">
                <img src="/img/upload_file_1.png" title="carica un file" style="width:32px" />
            </a>
        </li>
                <?php $form = ActiveForm::begin([
                    'id' => 'upload-form',
                    'action' => ['folder/upload-files','projectId'=>$pId],
                    'method'=>"post",
                    'options' => ['enctype' => 'multipart/form-data']
                ]); ?>
                    <input type="hidden" name="folderId" id="upload-folder-id">
                    <!-- input nascosto per selezionare file multipli -->
                    <?= Html::fileInput('files[]', null, [
                        'id' => 'hidden-file-input',
                        'multiple' => true,
                        'style' => 'display:none;',
                    ]) ?>

                <?php ActiveForm::end(); ?>
            
        
        <!-- Notifications Dropdown Menu -->
        <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li> -->
        <!-- <li class="nav-item">
            <?php //echo Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li> -->
    </ul>
</nav>
<!-- /.navbar -->

<?php
// JS per gestire il click
// $js = <<<JS
// // al click del pulsante finto, apri il file dialog
// $('#input-700').on('click', function(e) {
//     e.preventDefault();

// // recupero il nodo selezionato in jstree
//     var selected = $('#treefolder').jstree('get_selected', true);
//     if (selected.length > 0) {
//         var folderId = selected[0].id;
//         $('#upload-folder-id').val(folderId); // hidden input nel form
//     } else {
//         alert("Seleziona una cartella prima di caricare file.");
//         return;
//     }


//     $('#hidden-file-input').click();
// });

// // quando vengono scelti i file, sottometti il form
// $('#hidden-file-input').on('change', function() {
//     var formData = new FormData($('#upload-form')[0]);

//     $.ajax({
//         url: $('#upload-form').attr('action'),
//         type: 'POST',
//         data: formData,
//         processData: false,
//         contentType: false,
//         success: function(res) {
//             // se l'upload è andato a buon fine
//             var folderId = $('#upload-folder-id').val();

//             // 1) seleziona il nodo
//             // $('#treefolder').jstree('deselect_all');
//             $('#treefolder').jstree('select_node', folderId);

//             // // 2) opzionale: ricarica i figli
//             // $('#treefolder').jstree('refresh_node', folderId);
//         },
//         error: function() {
//             alert("Errore durante l'upload.");
//         }
//     });
//     // $('#upload-form').submit();
// });
// JS;

// $this->registerJs($js);