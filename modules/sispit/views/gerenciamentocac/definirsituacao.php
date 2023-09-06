<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPlanoRelatorio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-parecer-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'situacao_estagio_probatorio')->label(
        "O docente <em>{$model->pessoa->nome}</em> precisará submeter um RIT parcial do 1º semestre?"
        )->dropDownList([0 => 'NÃO', 1 => 'SIM'])?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
