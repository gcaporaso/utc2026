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

    
    //app\assets\AppAsset::register($this);
    

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
         <!-- Theme style -->
<!--        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">-->
        <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
<!--        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">-->
        <link href="css/all.css" rel="stylesheet"> <!--load all styles -->
<!--        <script src="https://kit.fontawesome.com/d52f411a0f.js" crossorigin="anonymous"></script>-->
        <?php //Html::csrfMetaTags() ?>
        <title><?php Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <?= Html::csrfMetaTags() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-collapse sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?php 
        if (isset(Yii::$app->user)?Yii::$app->user->can('RUP Pratiche Edilizie'):false) {
       echo $this->render('left.php',['directoryAsset' => $directoryAsset]);
       echo $this->render('right.php',['directoryAsset' => $directoryAsset]);
        }
        ?>
        <?= Alert::widget() ?>
        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
        
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
