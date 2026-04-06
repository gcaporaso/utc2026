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
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
<?php $this->endBlock(); ?>

<h1 style="margin:25px 0px 0 25px">Projects</h1>
<p style="display:flex;justify-content:flex-end;">
    <?= Html::a('<i class="fas fa-folder"></i> Create New', ['project/create'], ['class' => 'btn btn-primary', 'style' => 'margin:25px;']) ?>
</p>

<?php foreach ($projects as $item): ?>
    <?php if ($item->creator_user_id == $userId || (isset($item->users) && in_array($userId, array_column($item->users, 'id')))): ?>
        <a href="<?= Url::to(['folders/redirect', 'projectId' => $item->id]) ?>">
            <div class="card" style="width:350px; height:auto; display:inline-block; margin:0px 25px 20px">
                <div style="height:auto;">
                    <img height="250" src="/dist/img/Project-placeholder.png" class="card-img-top rounded-top" alt="...">
                </div>
                <div class="card-body">
                    <b style="color:saddlebrown; font-size:21px"> <?= Html::encode($item->name) ?> </b>
                    <br>
                    <p class="card-text" style="color:black; text-overflow: ellipsis; white-space:nowrap; overflow: hidden;">
                        <?= Html::encode($item->description) ?>
                    </p>
                    <hr>
                    <?= Html::a('<i class="fa-solid fa-circle-info fa-lg"></i> Details', ['project/details', 'id' => $item->id], ['class' => 'btn btn-primary btn-sm', 'style' => 'margin:5px']) ?>

                    <?php if ($userId == $item->creator_user_id): ?>
                        <?= Html::a('<i class="fas fa-trash"></i> Delete', ['project/delete', 'id' => $item->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'style' => 'margin:5px',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this project?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::a('<i class="fa-solid fa-user-plus"></i> Add Members', ['project/add-user', 'projectId' => $item->id], ['class' => 'btn btn-secondary btn-sm', 'style' => 'margin:5px']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    <?php endif; ?>
<?php endforeach; ?>

<?php $this->registerJsFile('/plugins/jquery/jquery.min.js'); ?>
<?php $this->registerJsFile('/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>
<?php $this->registerJsFile('/dist/js/adminlte.min.js'); ?>
<?php $this->registerJsFile('/dist/js/demo.js'); ?>
