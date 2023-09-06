<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisligaLigaIntegranteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisliga-liga-integrante-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_liga_integrante') ?>

    <?= $form->field($model, 'id_liga_academica') ?>

    <?= $form->field($model, 'id_integrante_externo') ?>

    <?= $form->field($model, 'id_pessoa') ?>

    <?= $form->field($model, 'funcao') ?>

    <?php // echo $form->field($model, 'id_aluno') ?>

    <?php // echo $form->field($model, 'carga_horaria') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
