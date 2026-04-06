<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User[] $users */  // Lista utenti passata come $users
/** @var app\models\Project $project */ // Progetto corrente passato come $project

$this->title = 'Aggiunta Partecipante al Progetto';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//$numUsers = count($projectUsers);
?>

<head>
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
</head>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (count($project->users) != $numU): ?>
    <table class="table">
        <thead>
            <tr>
                <th><?= Html::encode('Nome') ?></th>
                <th><?= Html::encode('Email') ?></th>
                <th><?= Html::encode('Positione') ?></th>
                <th><?= Html::encode('Tipo Organizazzione') ?></th>
                <th><?= Html::encode('Disciplina') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <?php
                // Controlla se l'utente NON è già membro del progetto
                $isMember = false;
                foreach ($user->workonProjects as $p) {
                    if ($p->Id == $project->Id) {
                        $isMember = true;
                        break;
                    }
                }
                if (!$isMember):
                ?>
                <tr>
                    <td><?= Html::encode($user->Name) ?></td>
                    <td><?= Html::encode($user->email) ?></td>
                    <td><?= Html::encode($user->Position) ?></td>
                    <td><?= Html::encode($user->OrganizationType) ?></td>
                    <td><?= Html::encode($user->Discipline) ?></td>
                    <td>
                        <?= Html::a(
                            '<i class="fa-solid fa-user-plus"></i> &nbsp; Aggiungi',
                            ['project/added-user', 'ProjectUser' => $user->id, 'projectId' => $project->Id],
                            ['class' => 'btn btn-info btn-sm']
                        ) ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= Html::a('<i class="fa-solid fa-circle-left"></i> &nbsp;Torna a Elenco Progetti', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
&nbsp;&nbsp;
<?= Html::a('<i class="fa-solid fa-users"></i> Partecipanti al Progetto', ['project/view-user', 'projectId' => $project->Id], ['class' => 'btn btn-outline-dark']) ?>
