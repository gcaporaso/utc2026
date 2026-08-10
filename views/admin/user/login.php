<?php
/** @var yii\web\View $this */
/** @var mdm\admin\models\form\Login $model */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Accesso — UTC BIM';
?>
<div class="login-page" style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:#1a1a2e;">

  <div class="login-box" style="width:360px;">

    <!-- Logo -->
    <div class="text-center mb-4">
      <img src="<?= Yii::$app->request->baseUrl ?>/img/logo.svg"
           onerror="this.style.display='none'"
           alt="UTC BIM" style="height:64px;">
      <h4 class="mt-2" style="color:#fff;font-weight:300;letter-spacing:2px;">UTC BIM</h4>
      <p style="color:#aaa;font-size:12px;">Ufficio Tecnico Comunale</p>
    </div>

    <div class="card" style="border-radius:10px;box-shadow:0 8px 32px rgba(0,0,0,.4);">
      <div class="card-body p-4">
        <h5 class="card-title text-center mb-4" style="color:#333;font-weight:500;">Accedi al portale</h5>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible mb-3">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <?= Yii::$app->session->getFlash('success') ?>
        </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible mb-3">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <?= Yii::$app->session->getFlash('error') ?>
        </div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin([
            'id'      => 'login-form',
            'options' => ['autocomplete' => 'on'],
        ]); ?>

        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text bg-white border-right-0">
              <i class="fas fa-user text-muted"></i>
            </span>
          </div>
          <?= $form->field($model, 'username', [
              'options'  => ['class' => 'flex-grow-1 mb-0'],
              'template' => '{input}{error}',
          ])->textInput([
              'placeholder'  => 'Nome utente',
              'class'        => 'form-control border-left-0',
              'autofocus'    => true,
              'autocomplete' => 'username',
          ]) ?>
        </div>

        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text bg-white border-right-0">
              <i class="fas fa-lock text-muted"></i>
            </span>
          </div>
          <?= $form->field($model, 'password', [
              'options'  => ['class' => 'flex-grow-1 mb-0'],
              'template' => '{input}{error}',
          ])->passwordInput([
              'placeholder'  => 'Password',
              'class'        => 'form-control border-left-0',
              'autocomplete' => 'current-password',
          ]) ?>
        </div>

        <div class="d-flex align-items-center mb-3">
          <?= $form->field($model, 'rememberMe', [
              'options'  => ['class' => 'mb-0'],
              'template' => '{input} {label}{error}',
          ])->checkbox(['label' => false])->label('Ricordami') ?>
        </div>

        <?= Html::submitButton(
            '<i class="fas fa-sign-in-alt mr-2"></i>Accedi',
            ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']
        ) ?>

        <?php ActiveForm::end(); ?>

      </div>
    </div>

    <p class="text-center mt-3">
      <a href="<?= \yii\helpers\Url::to(['/admin/user/signup']) ?>"
         style="color:#aaa;font-size:12px;">
        Non hai un account? Registrati
      </a>
    </p>
    <p class="text-center mt-1" style="color:#666;font-size:11px;">
      &copy; <?= date('Y') ?> Comune — UTC BIM
    </p>
  </div>

</div>

<?php
$this->registerCss('
  .field-loginform-username, .field-loginform-password { flex-grow: 1; }
  .field-loginform-username .form-control,
  .field-loginform-password .form-control { border-left: none; }
  .input-group-text { border-right: none; }
  .help-block { font-size: 12px; color: #e74c3c; margin-top: 2px; }
  .input-group { align-items: stretch; }
  .input-group > .flex-grow-1 { display: flex; flex-direction: column; }
');
?>
