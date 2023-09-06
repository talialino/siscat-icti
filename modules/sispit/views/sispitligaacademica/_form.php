<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\number\NumberControl;

/* @var $this yii\web\View /*/
/* @var $model app\modules\sispit\models\SispitLigaAcademica */
/* @var $form yii\widgets\ActiveForm */

$ligas = ArrayHelper::map($model->listaLigas(),'id_liga_academica','nome');
?>

<div class="sispit-liga-academica-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true, 
        'validationUrl' => ['sispitligaacademica/validate',
            'id' => $model->isNewRecord ? $model->id_plano_relatorio : $model->id_sispit_liga_academica, 'plano' => $model->isNewRecord
    ]]); ?>

    <?= $form->field($model, 'id_liga_academica')->widget(Select2::class, [
        'data' => $ligas,
        'options' => ['placeholder' => 'Selecione a liga acadêmica'],
        'pluginOptions' => ['allowClear' =>true]
    ])?>

    <?php if(Yii::$app->session->get('siscat_ano')->suplementar):?>
    <?= Html::activeHiddenInput($model,'semestre',['value' => 1]) ?>
    <?php else:?>
    <?= $form->field($model, 'semestre')->radioList([1 => '1º semestre', 2 => '2° semestre']) ?>
    <?php endif;?>

    <?= $form->field($model, 'funcao')->dropDownList($model::FUNCAO) ?>

    <?= $form->field($model, 'carga_horaria')->widget(NumberControl::class,[
        'readonly' => !$model->planoRelatorio->isEditable(),
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
            'max' => 2,
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
