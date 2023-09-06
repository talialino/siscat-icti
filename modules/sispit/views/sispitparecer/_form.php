<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitParecer */
/* @var $form yii\widgets\ActiveForm */

//Define os valores possíveis para planorelatorio->status
$aprovado = ($model->tipo_parecerista == $model::PARECERISTA_NUCLEO ? 3 : 7) + (10 * $model->pit_rit);
$reprovado = $aprovado + 1;


?>

<div class="sispit-parecer-form">

    <h3 style="text-align:center" class="panel-title">SEU PARECER</h3>

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model->planoRelatorio,'status')->label(null)->dropDownList([$aprovado => 'Aprovado', $reprovado => 'Necessita de correções'])?>

    <?= $form->field($model, 'parecer')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
