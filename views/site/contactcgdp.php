<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

$this->title = $model->getTitulo();
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-contact">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            <?=$model->getMensagemResposta()?>
        </div>

    <?php else: ?>

        <div class="mensagem-inicial">
            <?=$model->getMensagemInicial()?>
        </div>

        <div class="row" style="margin-top: 10px;">
            <div class="login-form col-md-6 col-md-offset-3">
            <div class="box box-primary">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'siape') ?>

                    <?= $form->field($model, 'loginUfba') ?>

                    <?= $form->field($model, 'telefones') ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>
