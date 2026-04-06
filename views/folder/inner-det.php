<?php
/** @var app\models\Folder $folder */
/** @var int $projectId */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<h1>Dettagli Cartella: <?= Html::encode($folder->Name) ?></h1>
<p>Progetto: <?= Html::encode($folder->project->Name) ?></p>
<p>Creato da: <?= Html::encode($folder->creatorUser->username) ?></p>

<h3>Sotto-cartelle</h3>
<ul>
<?php foreach ($folder->innerFolders as $inner): ?>
    <li>
        <?= Html::a(Html::encode($inner->Name), ['folder/inner-det', 'id' => $inner->Id, 'projectId' => $projectId]) ?>
    </li>
<?php endforeach; ?>
</ul>

<h3>File</h3>
<ul>
<?php foreach ($folder->files as $file): ?>
    <li>
        <?= Html::encode($file->name) ?> (<?= Html::encode($file->type) ?>)
        - Creato da: <?= Html::encode($file->creatorUserId->Name) ?>
        <?= Html::a('Download', ['folder/download-file', 'fileName' => $file->Name]) ?>
    </li>
<?php endforeach; ?>
</ul>

<p>
    <?= Html::a('Crea sotto-cartella', ['folder/create-inner-folder', 'id' => $folder->Id,'projectId'=>$folder->ProjectId], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Carica File', ['folders/upload-files', 'id' => $folder->Id], ['class' => 'btn btn-success']) ?>
</p>
