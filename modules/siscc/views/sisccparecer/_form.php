<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\siscc\models\SisccParecer;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccParecer */
/* @var $form yii\widgets\ActiveForm */

//Define os valores possíveis para planorelatorio->status
$aprovado = ($model->tipo_parecerista == $model::PARECERISTA_COLEGIADO ? 3 : 8);
$reprovado = $aprovado + 1;
?>

<div class="siscc-parecer-form">

    <h3 style="text-align:center" class="panel-title">SEU PARECER</h3>

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model->programaComponenteCurricular,'situacao')->label(null)->dropDownList([$aprovado => 'Aprovado', $reprovado => 'Necessita de correções'])?>

    <?= $form->field($model, 'parecer')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
