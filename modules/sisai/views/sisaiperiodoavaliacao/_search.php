<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiPeriodoAvaliacaoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisai-periodo-avaliacao-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_periodo_avaliacao') ?>

    <?= $form->field($model, 'id_semestre') ?>

    <?= $form->field($model, 'tipo_avaliacao') ?>

    <?= $form->field($model, 'data_inicio') ?>

    <?= $form->field($model, 'data_fim') ?>

    <?php // echo $form->field($model, 'questionarios') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
