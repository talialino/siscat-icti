<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\sisliga\models\SisligaParecer;

/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaParecer */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="sisliga-parecer-form">

    <h3 style="text-align:center" class="panel-title">SEU PARECER</h3>

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model->documento,'situacao')->label(null)->dropDownList([3 => 'Aprovado', 4 => 'Necessita de correções'])?>

    <?= $form->field($model, 'parecer')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
