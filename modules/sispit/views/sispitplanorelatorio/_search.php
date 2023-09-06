<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPlanoRelatorioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-plano-relatorio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_plano_relatorio') ?>

    <?= $form->field($model, 'id_pessoa') ?>

    <?= $form->field($model, 'ano') ?>

    <?= $form->field($model, 'data_homologacao_nucleo_pit') ?>

    <?= $form->field($model, 'data_homologacao_cac_pit') ?>

    <?php // echo $form->field($model, 'data_preenchimento_pit') ?>

    <?php // echo $form->field($model, 'data_homologacao_nucleo_rit') ?>

    <?php // echo $form->field($model, 'data_homologacao_cac_rit') ?>

    <?php // echo $form->field($model, 'data_preenchimento_rit') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
