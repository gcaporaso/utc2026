<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User[] $model */
/** @var app\models\Project $ThisProject */
/** @var string $userId */

$this->title = $project->Name . ': componenti';
?>

<head>
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
</head>

<h1><?= Html::encode($project->Name) ?> - Partecipanti al Progetto</h1>
<?php //echo print_r($projectUser) ?>
<?php //echo count($projectUser); ?>
<?php // foreach ($projectUser as $item): 
       // echo var_dump(array_column($item->workonProjects, 'Id'));
      // endforeach;
    ?>
<?php // var_dump($project->users); ?>
<?php if (count($projectUser) === 0): ?>
    <!-- Nessun utente -->
     <p>Nessun Partecipante</p>
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
            <?php foreach ($projectUser as $item): ?>
                <?php if (in_array($project->Id, array_column($item->workonProjects, 'Id'))): ?>
                    <tr>
                        <td><i class="fas fa-user"></i></td>
                        <td><?= Html::encode($item->Name) ?></td>
                        <td><?= Html::encode($item->email) ?></td>
                        <td><?= Html::encode($item->Position) ?></td>
                        <td><?= Html::encode($item->OrganizationType) ?></td>
                        <td><?= Html::encode($item->Discipline) ?></td>
                        <?php //if ($userId === $project->CreatorUserId): ?>  
                            <?php if (Yii::$app->user->id === $project->CreatorUserId): ?>
                            <td>
                                <?= Html::a('<i class="fa-solid fa-user-slash"></i> &nbsp; Escludi', 
                                    ['delete-user', 'ProjectUser' => $item->id, 'projectId' => $project->Id], 
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

<?= Html::a('<i class="fa-regular fa-building"></i> Vai al Progetto', 
    ['folder/index', 'projectId' => $project->Id], 
    ['class' => 'btn btn-outline-dark']) ?>
&nbsp;&nbsp;

<?php if ($project->CreatorUserId === Yii::$app->user->id): ?>
    <?= Html::a('<i class="fa-solid fa-user-plus"></i> Aggiungi Componenti', 
        ['add-user', 'projectId' => $project->Id], 
        ['class' => 'btn btn-outline-secondary']) ?>
<?php else: ?>
    <?= Html::a('<i class="fa-solid fa-circle-info fa-lg"></i> &nbsp; Dettagli', 
        ['details', 'id' => $project->Id], 
        ['class' => 'btn btn-outline-secondary']) ?>
<?php endif; ?>
