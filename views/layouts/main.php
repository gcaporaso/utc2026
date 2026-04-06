<?php

/* @var $this \yii\web\View */
/* @var $content string */
use app\assets\MyAdminLteAsset;
use yii\helpers\Html;
//use dominus77\sweetalert2;
//use hail812\adminlte\widgets\Alert;

\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
//MyAdminLteAsset::register($this);

//$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

// $assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
// $publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
//$this->registerJsFile($publishedRes[1].'/control_sidebar.js', ['depends' => '\hail812\adminlte3\assets\AdminLteAsset']);
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
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
<?php $this->beginBody() ?>

<div class="wrapper">
    <!-- <?php //\dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]) ?>  -->
    
    <!-- Navbar -->
    <?php // $this->render('navbar', ['assetDir' => $assetDir]) ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->

    <?= $this->render('sidebar', ['content' => $content]) ?> 

    <!-- Content Wrapper. Contains page content -->
    <?= $this->render('content', ['content' => $content]) ?>
    
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <?php // $this->render('control-sidebar') ?>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <?php //= $this->render('footer') ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?> 