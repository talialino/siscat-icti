<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\SiscoaeAsset;
SiscoaeAsset::register($this);

$this->title = 'Carregar Arquivo CSV';
$this->params['breadcrumbs'][] = ['label' => 'Formulário Socioeconômico', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-upload">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

        <div class="row" style="margin-top: 10px;">
            <?php if($mensagem):?>
                <p><?=$mensagem?></p>
            <?php else:?>
                <div style="padding:20px;margin: 0 auto 20px; max-width: 700px;" class="box box-primary">
                <?php $form = ActiveForm::begin(['id' => 'upload-form']); ?>

                    <?= $form->field($model, 'arquivoCsv')->fileInput() ?>

                    <?= $form->field($model, 'nome') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'upload-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
                </div>
            </div>
            <?php endif;?>
        </div>
</div>
