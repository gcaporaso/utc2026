<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Task[] $tasks */
/** @var int $projectId */
/** @var string $userId */

$this->title = 'Tasks';
?>
<head>
    <script src="https://kit.fontawesome.com/056ba6c557.js" crossorigin="anonymous"></script>
</head>

<h1><i class="fa-solid fa-list-check fa-lg"></i>&nbsp;Tasks</h1>

<p style="display:flex;justify-content:flex-end;">
    <?= Html::a(
        '<i class="fa-solid fa-calendar-plus"></i> &nbsp; Aggiungi Attività',
        ['task/create', 'ProjectId' => $projectId],
        ['class' => 'btn btn-primary', 'style' => 'margin:0px 25px;']
    ) ?>
</p>

<?php if (!empty($tasks) && array_filter($tasks, fn($t) => $t->ProjectId == $projectId)): ?>
    <table class="table" style="text-align:center">
        <thead>
            <tr>
                <th>Title</th>
                <th>Progress</th>
                <th>Priority</th>
                <th>Creation Date</th>
                <th>Deadline</th>
                <th>Creator User</th>
                <th>Assigned To User</th>
                <th>Assigned To Team</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($tasks as $item): ?>
            <?php if (
                $item->ProjectId == $projectId &&
                (
                    $userId == $item->CreatorUserId ||
                    ($item->AssignedtoUserId && $userId == $item->AssignedtoUserId) ||
                    $userId == $item->project->CreatorUserId ||
                    ($item->team && in_array($userId, array_column($item->team->users, 'id')))
                )
            ): ?>
                <tr>
                    <td>
                        <?= Html::a($item->Title, ['task/details', 'id' => $item->Id]) ?>
                    </td>
                    <td><?= Html::encode($item->Progress) ?></td>
                    <td style="text-align:left">
                        <?php
                        switch ($item->Priority) {
                            case 'Low':
                                echo Html::img('/Images/Green check .png', ['style' => 'width:15px']);
                                break;
                            case 'Normal':
                                echo Html::img('/Images/Blue check.png', ['style' => 'width:15px']);
                                break;
                            case 'High':
                                echo Html::img('/Images/Yellow Check.png', ['style' => 'width:15px']);
                                break;
                            case 'Critical':
                                echo Html::img('/Images/Red check.png', ['style' => 'width:15px']);
                                break;
                        }
                        ?>
                        <?= Html::encode($item->Priority) ?>
                    </td>
                    <td><?= Html::encode($item->CreationDate) ?></td>
                    <td><?= Html::encode($item->DeadLine) ?></td>
                    <td><?= Html::encode($item->creatorUser->Name ?? '') ?></td>
                    <td><?= Html::encode($item->assignedtoUser->Name ?? '') ?></td>
                    <td><?= Html::encode($item->team->Name ?? '') ?></td>
                    <td>
                        <?php if (
                            $userId == $item->CreatorUserId ||
                            ($item->AssignedtoUserId && $userId == $item->AssignedtoUserId) ||
                            ($item->team && $userId == $item->team->TeamLeaderId)
                        ): ?>
                            <?= Html::a(
                                '<i class="fa-solid fa-pen-to-square fa-lg"></i> &nbsp; Edit',
                                ['task/edit', 'ProjId' => $projectId, 'id' => $item->Id],
                                ['class' => 'btn btn-info']
                            ) ?>
                        <?php endif; ?>

                        <?php if ($userId == $item->CreatorUserId): ?>
                            <?= Html::a(
                                '<i class="fas fa-trash fa-lg"></i> &nbsp; Delete',
                                ['task/delete', 'ProjId' => $projectId, 'id' => $item->Id],
                                ['class' => 'btn btn-danger']
                            ) ?>
                        <?php endif; ?>

                        <?= Html::a(
                            '<i class="fa-solid fa-circle-info fa-lg"></i> &nbsp; Details',
                            ['task/details', 'id' => $item->Id],
                            ['class' => 'btn btn-primary']
                        ) ?>
