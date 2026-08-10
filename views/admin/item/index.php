<?php
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var mdm\admin\models\searchs\AuthItem $searchModel */
/** @var mdm\admin\components\ItemController $context */

use mdm\admin\components\RouteRule;
use mdm\admin\components\Configs;
use yii\helpers\Html;
use yii\helpers\Url;

$context = $this->context;
$labels  = $context->labels();
$isRole  = ($labels['Item'] === 'Role');
$icon    = $isRole ? 'shield-alt' : 'key';
$color   = $isRole ? 'primary' : 'secondary';
$this->title = $isRole ? 'Ruoli' : 'Permessi';

$rules = array_keys(Configs::authManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);
?>
<div class="content-wrapper" style="margin-left:10px!important">
  <section class="content-header">
    <h1><?= $this->title ?> <small><?= $isRole ? 'Ruoli di accesso al sistema' : 'Permessi operativi' ?></small></h1>
  </section>

  <section class="content">
    <div class="mb-3 d-flex justify-content-between align-items-center">
      <a href="<?= Url::to(['create']) ?>" class="btn btn-success btn-sm">
        <i class="fas fa-plus mr-1"></i> Nuovo <?= strtolower($labels['Item']) ?>
      </a>
      <div>
        <a href="<?= Url::to(['/admin/user']) ?>" class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-users mr-1"></i> Utenti
        </a>
        <a href="<?= Url::to(['/admin/assignment']) ?>" class="btn btn-sm btn-outline-secondary ml-1">
          <i class="fas fa-user-tag mr-1"></i> Assegnazioni
        </a>
      </div>
    </div>

    <div class="card card-outline card-<?= $color ?>">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-<?= $icon ?> mr-1"></i>
          <?= $this->title ?>
        </h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
          <thead class="thead-light">
            <tr>
              <th style="width:30px">#</th>
              <th>Nome</th>
              <th>Descrizione</th>
              <th style="width:100px"></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 0;
            foreach ($dataProvider->getModels() as $item):
                $i++;
            ?>
            <tr>
              <td class="text-muted"><?= $i ?></td>
              <td>
                <strong><?= Html::encode($item->name) ?></strong>
              </td>
              <td class="text-muted"><?= Html::encode($item->description) ?></td>
              <td style="white-space:nowrap">
                <a href="<?= Url::to(['view', 'id' => $item->name]) ?>"
                   class="btn btn-xs btn-outline-primary" title="Dettaglio">
                  <i class="fas fa-eye"></i>
                </a>
                <a href="<?= Url::to(['update', 'id' => $item->name]) ?>"
                   class="btn btn-xs btn-outline-secondary" title="Modifica">
                  <i class="fas fa-pencil-alt"></i>
                </a>
                <a href="<?= Url::to(['delete', 'id' => $item->name]) ?>"
                   class="btn btn-xs btn-outline-danger" title="Elimina"
                   data-method="post"
                   data-confirm="Eliminare '<?= Html::encode($item->name) ?>'?">
                  <i class="fas fa-trash"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if ($i === 0): ?>
            <tr><td colspan="4" class="text-center text-muted py-4">Nessun elemento.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </section>
</div>
