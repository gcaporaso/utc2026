<?php
use yii\helpers\Html;
?>
<div class="right_toolbar">
        <a href="#" title="Home">🏠</a>
        <?= Html::a('<i class="fa-solid fa-folder-plus fa-lg"></i>',
            ['folder/create', 'projectId' => $projectId],
            ['title'=>'Crea una nuova cartella']
        ) ?>
        <!-- <?php // Html::a('<i class="fa-solid fa-upload fa-lg"></i>',
            // ['folder/create', 'projectId' => $projectId],
            // ['title'=>'Crea una nuova cartella']
       // ) ?> -->
        <button id="btnUpload" title="Carica File">📤</button>
        <input type="file" id="myfileInput" style="display:none;">
        <a href="#" title="Cerca">🔍</a>
        <a href="#" title="Impostazioni">⚙️</a>
</div>
<?php
$uploadUrl = \yii\helpers\Url::to(['/folder/ajax-upload', 'projectId' => $this->params['projectId'] ?? null]);
$csrfToken = Yii::$app->request->csrfToken;

$js = <<<JS
$('#btnUpload').on('click', function() {
    $('#myfileInput').click();
});

$('#myfileInput').on('change', function() {
    var fileData = $('#myfileInput').prop('files')[0];
    if (!fileData) return;

    var formData = new FormData();
    formData.append('file', fileData);
    formData.append('_csrf', '$csrfToken');

    $.ajax({
        url: '$uploadUrl',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if(response.success) {
                alert('File caricato con successo!');
                location.reload(); // o aggiorna dinamicamente la lista
            } else {
                alert('Errore: ' + response.error);
            }
        },
        error: function() {
            alert('Errore di connessione.');
        }
    });
});
JS;

$this->registerJs($js);
?>
