<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User[] $model */
/** @var app\models\Project $ThisProject */
/** @var string $userId */

$this->title = $ThisProject->name . ' users';
?>

<head>
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
</head>

<h1><?= Html::encode($ThisProject->name) ?> users</h1>

<?php if (count($ThisProject->users) === 0): ?>
    <!-- Nessun utente -->
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th><?= Html::encode('Name') ?></th>
                <th><?= Html::encode('Email') ?></th>
                <th><?= Html::encode('Position') ?></th>
                <th><?= Html::encode('Organization Type') ?></th>
                <th><?= Html::encode('Discipline') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model as $item): ?>
                <?php if (in_array($ThisProject->id, array_column($item->workOnProjects, 'id'))): ?>
                    <tr>
                        <td><i class="fas fa-user"></i></td>
                        <td><?= Html::encode($item->name) ?></td>
                        <td><?= Html::encode($item->email) ?></td>
                        <td><?= Html::encode($item->position) ?></td>
                        <td><?= Html::encode($item->organizationType) ?></td>
                        <td><?= Html::encode($item->discipline) ?></td>
                        <?php if ($userId === $ThisProject->creatorUserId): ?>
                            <td>
                                <?= Html::a('<i class="fa-solid fa-user-slash"></i> &nbsp; Delete', 
                                    ['delete-user', 'ProjectUser' => $item->id, 'projectId' => $ThisProject->id], 
                                    ['class' => 'btn btn-info btn-sm']) ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<hr />

<?= Html::a('<i class="fa-regular fa-building"></i> Go To project', 
    ['folders/redirect', 'ProjectId' => $ThisProject->id], 
    ['class' => 'btn btn-outline-dark']) ?>
&nbsp;&nbsp;

<?php if ($ThisProject->creatorUserId === $userId): ?>
    <?= Html::a('<i class="fa-solid fa-user-plus"></i> Add Members', 
        ['add-user', 'projectId' => $ThisProject->id], 
        ['class' => 'btn btn-outline-secondary']) ?>
<?php else: ?>
    <?= Html::a('<i class="fa-solid fa-circle-info fa-lg"></i> &nbsp; Details', 
        ['details', 'id' => $ThisProject->id], 
        ['class' => 'btn btn-outline-secondary']) ?>
<?php endif; ?>
