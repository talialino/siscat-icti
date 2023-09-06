<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiAlunoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisai-aluno-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_aluno') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'matricula') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'id_setor') ?>

    <?php // echo $form->field($model, 'ativo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
