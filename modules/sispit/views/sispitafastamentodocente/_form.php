<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\number\NumberControl;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitAfastamentoDocente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-afastamento-docente-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?php if(Yii::$app->session->get('siscat_ano')->suplementar):?>

        <?= Html::activeHiddenInput($model,'semestre',['value' => 1]) ?>

    <?php else:?>

        <?= $form->field($model, 'semestre')->radioList([1 => '1º semestre', 2 => '2° semestre']) ?>
    
    <?php endif;?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'eh_afastamento')->label('Esta ocorrência é um afastamento docente?')->dropDownList([1 => 'Sim', 0 => 'Não']) ?>

    <?= $form->field($model, 'nivel_graduacao')->dropDownList($model::NIVEL_GRADUACAO, ['prompt' => 'Selecione o nível de graduação']) ?>

    <?= $form->field($model, 'carga_horaria')->widget(NumberControl::className(),[
        'readonly' => !$model->planoRelatorio->isEditable(),
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
            'max' => 40,
        ]
    ]) ?>

    <?= $form->field($model, 'data_inicio')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,]) ?>

    <?= $form->field($model, 'data_fim')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,])?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
