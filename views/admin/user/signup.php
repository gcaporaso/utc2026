<?php
/** @var yii\web\View $this */
/** @var mdm\admin\models\form\Signup $model */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Registrazione — UTC BIM';
?>
<div class="login-page" style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:#1a1a2e;">

  <div class="login-box" style="width:420px;">

    <div class="text-center mb-4">
      <img src="<?= Yii::$app->request->baseUrl ?>/img/logo.svg"
           onerror="this.style.display='none'"
           alt="UTC BIM" style="height:64px;">
      <h4 class="mt-2" style="color:#fff;font-weight:300;letter-spacing:2px;">UTC BIM</h4>
      <p style="color:#aaa;font-size:12px;">Registrazione nuovo utente</p>
    </div>

    <div class="card" style="border-radius:10px;box-shadow:0 8px 32px rgba(0,0,0,.4);">
      <div class="card-body p-4">
        <h5 class="card-title text-center mb-1" style="color:#333;font-weight:500;">Crea account</h5>
        <p class="text-center text-muted mb-4" style="font-size:12px;">
          Dopo la registrazione il tuo account dovrà essere attivato dall'amministratore.
        </p>

        <?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>

        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text bg-white border-right-0">
              <i class="fas fa-user text-muted"></i>
            </span>
          </div>
          <?= $form->field($model, 'username', [
              'options'  => ['class' => 'flex-grow-1 mb-0'],
              'template' => '{input}{error}',
          ])->textInput(['placeholder' => 'Nome utente', 'class' => 'form-control border-left-0', 'autofocus' => true]) ?>
        </div>

        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text bg-white border-right-0">
              <i class="fas fa-envelope text-muted"></i>
            </span>
          </div>
          <?= $form->field($model, 'email', [
              'options'  => ['class' => 'flex-grow-1 mb-0'],
              'template' => '{input}{error}',
          ])->input('email', ['placeholder' => 'Email', 'class' => 'form-control border-left-0']) ?>
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
          ])->passwordInput(['placeholder' => 'Password (min. 6 caratteri)', 'class' => 'form-control border-left-0']) ?>
        </div>

        <div class="input-group mb-4">
          <div class="input-group-prepend">
            <span class="input-group-text bg-white border-right-0">
              <i class="fas fa-lock text-muted"></i>
            </span>
          </div>
          <?= $form->field($model, 'retypePassword', [
              'options'  => ['class' => 'flex-grow-1 mb-0'],
              'template' => '{input}{error}',
          ])->passwordInput(['placeholder' => 'Conferma password', 'class' => 'form-control border-left-0']) ?>
        </div>

        <?= Html::submitButton(
            '<i class="fas fa-user-plus mr-2"></i>Registrati',
            ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']
        ) ?>

        <?php ActiveForm::end(); ?>

      </div>
    </div>

    <p class="text-center mt-3">
      <a href="<?= \yii\helpers\Url::to(['/admin/user/login']) ?>"
         style="color:#aaa;font-size:12px;">
        Hai già un account? Accedi
      </a>
    </p>

  </div>
</div>

<?php
$this->registerCss('
  .flex-grow-1 { flex-grow: 1; }
  .flex-grow-1 .form-control { border-left: none; }
  .input-group-text { border-right: none; }
  .help-block { font-size: 12px; color: #e74c3c; margin-top: 2px; }
  .input-group { align-items: stretch; }
  .input-group > .flex-grow-1 { display: flex; flex-direction: column; }
');
?>
