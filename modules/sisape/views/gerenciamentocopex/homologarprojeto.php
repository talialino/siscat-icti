<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

?>

<div class="sisape-aprovar-programa">

    <div class="sisape-parecer-form">
    
        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

        <?= $form->field($model, 'data_homologacao_congregacao')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,])?>

        <?= $form->field($model, 'sessao_congregacao') ?>

        <?= $form->field($model, 'tipo_sessao_congregacao')->dropDownList([1 => 'Ordinária', 2 => 'Extraordinária']) ?>

        <div class="form-group">
            <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>