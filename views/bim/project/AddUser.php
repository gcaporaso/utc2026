<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User[] $users */  // Lista utenti passata come $users
/** @var app\models\Project $project */ // Progetto corrente passato come $project

$this->title = 'Add User';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$numUsers = count($users);
?>

<head>
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
</head>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (count($project->users) != $numUsers): ?>
    <table class="table">
        <thead>
            <tr>
                <th><?= Html::encode('Name') ?></th>
                <th><?= Html::encode('Email') ?></th>
                <th><?= Html::encode('Position') ?></th>
                <th><?= Html::encode('Organization type') ?></th>
                <th><?= Html::encode('Discipline') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <?php
                // Controlla se l'utente NON è già membro del progetto
                $isMember = false;
                foreach ($user->workonProjects as $p) {
                    if ($p->id == $project->id) {
                        $isMember = true;
                        break;
                    }
                }
                if (!$isMember):
                ?>
                <tr>
                    <td><?= Html::encode($user->name) ?></td>
                    <td><?= Html::encode($user->email) ?></td>
                    <td><?= Html::encode($user->position) ?></td>
                    <td><?= Html::encode($user->organizationType) ?></td>
                    <td><?= Html::encode($user->discipline) ?></td>
                    <td>
                        <?= Html::a(
                            '<i class="fa-solid fa-user-plus"></i> &nbsp; Add',
                            ['project/added-user', 'ProjectUser' => $user->id, 'projectId' => $project->id],
                            ['class' => 'btn btn-info btn-sm']
                        ) ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= Html::a('<i class="fa-solid fa-circle-left"></i> &nbsp;Back to All Projects', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
&nbsp;&nbsp;
<?= Html::a('<i class="fa-solid fa-users"></i> Show Project Members', ['project/view-user', 'projectId' => $project->id], ['class' => 'btn btn-outline-dark']) ?>
