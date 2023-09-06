<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisrh-setor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_setor') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'id_setor_responsavel') ?>

    <?= $form->field($model, 'codigo') ?>

    <?= $form->field($model, 'sigla') ?>

    <?php // echo $form->field($model, 'eh_colegiado') ?>

    <?php // echo $form->field($model, 'eh_nucleo_academico') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app','Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app','Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
