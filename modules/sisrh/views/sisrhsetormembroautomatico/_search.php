<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetorMembroAutomaticoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisrh-setor-membro-automatico-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_membro_automatico') ?>

    <?= $form->field($model, 'id_setor_origem') ?>

    <?= $form->field($model, 'funcao_origem') ?>

    <?= $form->field($model, 'id_setor_destino') ?>

    <?= $form->field($model, 'funcao_destino') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
