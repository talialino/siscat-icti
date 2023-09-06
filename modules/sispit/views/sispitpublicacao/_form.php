<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPublicacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-publicacao-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?php if(Yii::$app->session->get('siscat_ano')->suplementar):?>
    <?= Html::activeHiddenInput($model,'semestre',['value' => 1]) ?>
    <?php else:?>
    <?= $form->field($model, 'semestre')->radioList([1 => '1º semestre', 2 => '2° semestre']) ?>
    <?php endif;?>

    <?= $form->field($model, 'editora')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fonte_financiadora')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
