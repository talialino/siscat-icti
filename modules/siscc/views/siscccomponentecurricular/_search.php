<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccComponenteCurricularSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siscc-componente-curricular-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_componente_curricular') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'codigo_componente') ?>

    <?= $form->field($model, 'ch_teorica') ?>

    <?= $form->field($model, 'ch_pratica') ?>

    <?php // echo $form->field($model, 'ch_estagio') ?>

    <?php // echo $form->field($model, 'modulo_teoria') ?>

    <?php // echo $form->field($model, 'modulo_pratica') ?>

    <?php // echo $form->field($model, 'modulo_estagio') ?>

    <?php // echo $form->field($model, 'ementa') ?>

    <?php // echo $form->field($model, 'ativo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
