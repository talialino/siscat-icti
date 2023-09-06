<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use app\modules\sisrh\models\SisrhOcorrencia;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhAfastamento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisrh-afastamento-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'id_ocorrencia')->dropDownList(ArrayHelper::map(SisrhOcorrencia::find()->orderby('justificativa')->all(),'id_ocorrencia','justificativa'),['prompt' => '']) ?> 

    <?= $form->field($model, 'inicio')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,]) ?>

    <?= $form->field($model, 'termino')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,])  ?>

    <?= $form->field($model, 'observacao')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
