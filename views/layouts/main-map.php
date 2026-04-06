<?php
use yii\helpers\Html;
use app\widgets\Alert;
/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

//    if (class_exists('backend\assets\AppAsset')) {
//        backend\assets\AppAsset::register($this);
//    } else {
//        app\assets\AppAsset::register($this);
//    }
//
//    dmstr\web\AdminLteAsset::register($this);

//    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    
\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
//$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
//$this->registerJsFile($publishedRes[1].'/control_sidebar.js', ['depends' => '\hail812\adminlte3\assets\AdminLteAsset']);
    
    
    
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
<!--        <script src="https://kit.fontawesome.com/d52f411a0f.js" crossorigin="anonymous"></script>-->
        <?php //Html::csrfMetaTags() ?>
<!--        <title><?php //Html::encode($this->title) ?></title>-->
        <?php $this->head() ?>
        <?= Html::csrfMetaTags() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-collapse sidebar-mini" style="height:100%;margin:0!important;padding:0!important">
    <?php $this->beginBody() ?>
        <div class="container-fluid"  >    
    <div class="wrapper" >

        //<?php // $this->render(
//            'header.php',
//            ['directoryAsset' => $directoryAsset]
//        ) ?>

        //<?php 
//        if (isset(Yii::$app->user)?Yii::$app->user->can('RUP Pratiche Edilizie'):false) {
//       echo $this->render('left.php',['directoryAsset' => $directoryAsset]);
//        }
//        ?>
        //<?php // Alert::widget() ?>
        //<?php // $this->render(
//            'content-map.php',
//            ['content' => $content, 'directoryAsset' => $directoryAsset]
//        ) ?>

        
        <!-- Main Sidebar Container -->
    <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>

    <!-- Content Wrapper. Contains page content -->
    <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
    <!-- /.content-wrapper -->
    <!-- Control Sidebar -->
    <?php // $this->render('control-sidebar') ?>
    <!-- /.control-sidebar -->
   

    <!-- Main Footer -->
    <?php // $this->render('footer') ?>
        
    </div>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
