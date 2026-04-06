<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use app\models\Project;
use app\models\User;

/** @var yii\web\View $this */
/** @var Project[] $projects */
/** @var User $userLog */

$this->title = 'Index';
$this->params['breadcrumbs'][] = $this->title;

$userId = Yii::$app->user->id;
?>

<?php $this->beginBlock('head'); ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/dist/css/adminlte.min.css"> -->
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
<?php $this->endBlock(); ?>

<!-- <h1 style="margin:25px 0px 0 25px">Progetti</h1> -->
<p style="display:flex;justify-content:flex-end;">
    <?php // Html::a('<i class="fas fa-folder"></i> Crea Nuovo Progetto', ['project/create'], ['class' => 'btn btn-primary', 'style' => 'margin:25px;']) ?>
</p>

<?php foreach ($projects as $item): ?>
    <?php if ($item->CreatorUserId == $userId || (isset($item->users) && in_array($userId, array_column($item->users, 'id')))): ?>
        <a href="<?= Url::to(['folder/redirect', 'projectId' => $item->Id]) ?>">
            <div class="card" style="width:24%; height:auto; display:inline-block; margin:0px 5px 5px">
                <div style="height:auto;">
                    <img height="250" src="/dist/img/Project-placeholder.png" class="card-img-top rounded-top" alt="...">
                </div>
                <div class="card-body">
                    <b style="color:saddlebrown; font-size:21px"> <?= Html::encode($item->Name) ?> </b>
                    <br>
                    <p class="card-text" style="color:black; text-overflow: ellipsis; white-space:nowrap; overflow: hidden;">
                        <?= Html::encode($item->Description) ?>
                    </p>
                    <hr>
                    <?= Html::a('<i class="fa-solid fa-circle-info fa-lg"></i> Details', ['project/details', 'id' => $item->Id], ['class' => 'btn btn-primary btn-sm', 'style' => 'margin:5px']) ?>

                    <?php if ($userId == $item->CreatorUserId): ?>
                        <?= Html::a('<i class="fas fa-trash"></i> Delete', ['project/delete', 'id' => $item->Id], [
                            'class' => 'btn btn-danger btn-sm',
                            'style' => 'margin:5px',
                            'data' => [
                                'confirm' => 'Sei sicuro che vuoi eliminare questo progetto?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::a('<i class="fa-solid fa-user-plus"></i> Aggiungi Membro', ['project/add-user', 'projectId' => $item->Id], ['class' => 'btn btn-secondary btn-sm', 'style' => 'margin:5px']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    <?php endif; ?>
<?php endforeach; ?>

<?php $this->registerJsFile('/plugins/jquery/jquery.min.js'); ?>
<?php $this->registerJsFile('/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>
<?php $this->registerJsFile('/dist/js/adminlte.min.js'); ?>
<!-- <?php //$this->registerJsFile('/dist/js/demo.js'); ?> -->
