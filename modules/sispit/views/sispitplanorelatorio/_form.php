<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPlanoRelatorio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-plano-relatorio-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'ano')->textInput() ?>

    <?= $form->field($model, 'data_homologacao_nucleo_pit')->textInput() ?>

    <?= $form->field($model, 'data_homologacao_cac_pit')->textInput() ?>

    <?= $form->field($model, 'data_preenchimento_pit')->textInput() ?>

    <?= $form->field($model, 'data_homologacao_nucleo_rit')->textInput() ?>

    <?= $form->field($model, 'data_homologacao_cac_rit')->textInput() ?>

    <?= $form->field($model, 'data_preenchimento_rit')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
