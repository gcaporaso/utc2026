<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

//use dominus77\sweetalert2;
//use hail812\adminlte\widgets\Alert;

\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
//$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
//$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
//$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
//$this->registerJsFile($publishedRes[1].'/control_sidebar.js', ['depends' => '\hail812\adminlte3\assets\AdminLteAsset']);
//$this->registerCssFile('css/right_tool_bar.css', ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerCssFile('css/right_tool_bar.css');
$this->registerCssFile('../../plugins/jstree/themes/default/style.min.css');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
<!--    <meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php // $this->registerCsrfMetaTags() ?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="../../plugins/jquery/jquery.min.js"></script> 
    <!-- jsTree -->
    <!-- <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script> -->
    <!-- bs-custom-file-input -->
    <script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="../../dist/js/adminlte.min.js?v=3.2.0"></script> 
    <script src="../../plugins/jstree/jstree.min.js"></script> 

</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
<?php $this->beginBody() ?>


<div class="wrapper">
    <!-- <?php //\dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]) ?>  -->
    
    <!-- Navbar 'assetDir' => $assetDir, -->
      
    <?= $this->render('top_project_main_bar') ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?= $this->render('left_project_main_bar', ['content' => $content]) ?> <!-- , 'assetDir' => $assetDir]) ?> -->

    <!-- Content Wrapper. Contains page content -->
    <?= $this->render('content', ['content' => $content]) ?> <!--, 'assetDir' => $assetDir]) ?> -->
    
    <!-- /.content-wrapper -->

    <!-- Control Sidebar control-sidebar -->
    <!-- <?php // $this->render('control-sidebar') ?> -->
    <!-- /.control-sidebar -->

    <!-- <?php // $this->render('right_bar', ['projectId'=>$projectId]) ?>  -->
    
    <!-- Main Footer -->
    <?php //= $this->render('footer') ?>
</div>
   

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?> 
<!-- <script>$("#bim_control_sidebar").ControlSidebar('toggle');</script> -->