<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitParticipacaoEvento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-participacao-evento-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'nome')->textInput() ?>

    <?php if(Yii::$app->session->get('siscat_ano')->suplementar):?>
    <?= Html::activeHiddenInput($model,'semestre',['value' => 1]) ?>
    <?php else:?>
    <?= $form->field($model, 'semestre')->radioList([1 => '1º semestre', 2 => '2° semestre']) ?>
    <?php endif;?>

    <?= $form->field($model, 'tipo_evento')->dropDownList($model::TIPO_EVENTO) ?>

    <?= $form->field($model, 'tipo_participacao_evento')->dropDownList($model::TIPO_PARTICIPACAO) ?>

    <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_inicio')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,]) ?>

    <?= $form->field($model, 'data_fim')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,])?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
