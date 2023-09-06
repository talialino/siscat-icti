<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitAno */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-ano-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'ano')->widget(NumberControl::classname(),[
            'maskedInputOptions' => ['digits' => 0, 'allowMinus' => false, 'min' => 2012, 'groupSeparator' => '', 'rightAlign' => false],
    ])?>

    <?= $form->field($model, 'suplementar')->dropDownList([0 => 'Não', 1 => 'Sim']) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
