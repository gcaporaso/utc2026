
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Edilizia */

$this->title = Yii::t('app', 'Inserimento Nuovo Certificato Destinazione Urbanistica');
$this->params['breadcrumbs'][] = ['label' => 'CDU', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cdu-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
