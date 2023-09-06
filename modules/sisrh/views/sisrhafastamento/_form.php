<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use app\modules\sisrh\models\SisrhOcorrencia;
use app\modules\sisrh\models\SisrhPessoa;
/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhAfastamento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisrh-afastamento-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
    <div class="row">
        <div class='col-md-12'>
        <?= $form->field($model, 'id_pessoa')->dropDownList(ArrayHelper::map(SisrhPessoa::getAtivos('id_pessoa,nome'),'id_pessoa','nome'),['prompt' => '']) ?>
        </div>
        <div class='col-md-6'>
        <?= $form->field($model, 'inicio')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,]) ?>
        </div>
        <div class='col-md-6'>
        <?= $form->field($model, 'termino')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,])  ?>
        </div>
        <div class='col-md-12'>
        <?= $form->field($model, 'id_ocorrencia')->dropDownList(ArrayHelper::map(SisrhOcorrencia::find()->orderby('justificativa')->all(),'id_ocorrencia','justificativa'),['prompt' => '']) ?> 
        </div>
        <div class='col-md-12'>
        <?= $form->field($model, 'observacao')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-12 form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
