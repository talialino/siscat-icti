<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

?>

<div class="sisliga-aprovar-programa">

    <div class="sisliga-parecer-form">
    
        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

        <?= $form->field($model, 'data_homologacao_congregacao')->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,])?>

        <?= $form->field($model, 'sessao_congregacao') ?>

        <?= $form->field($model, 'tipo_sessao_congregacao')->dropDownList([1 => 'Ordinária', 2 => 'Extraordinária']) ?>

        <div class="form-group">
            <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>