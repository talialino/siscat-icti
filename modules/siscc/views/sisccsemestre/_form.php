<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\siscc\models\SisccSemestre;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccSemestre */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siscc-semestre-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ano')->textInput() ?>

    <?= $form->field($model, 'semestre')->dropDownList(SisccSemestre::OPCOESSEMESTRE) ?>

    <?= $form->field($model, 'remoto')->dropDownList([0 => 'Não', 1 => 'Sim']) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
