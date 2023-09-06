<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\number\NumberControl;
use app\modules\sisape\models\SisapeProjeto;

/* @var $this yii\web\View /*/
/* @var $model app\modules\sispit\models\SispitPesquisaExtensao */
/* @var $form yii\widgets\ActiveForm */

$projetos = ArrayHelper::map($model->listaProjetos(),'id_projeto','titulo');
?>

<div class="sispit-pesquisa-extensao-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true, 
        'validationUrl' => ['sispitpesquisaextensao/validate',
            'id' => $model->isNewRecord ? $model->id_plano_relatorio : $model->id_pesquisa_extensao, 'plano' => $model->isNewRecord
    ]]); ?>

    <?= $form->field($model, 'id_projeto')->widget(Select2::className(), [
        'data' => $projetos,
        'options' => ['placeholder' => 'Selecione o projeto'],
        'pluginOptions' => ['allowClear' =>true]
    ])?>

    <?php if(Yii::$app->session->get('siscat_ano')->suplementar):?>
    <?= Html::activeHiddenInput($model,'semestre',['value' => 1]) ?>
    <?php else:?>
    <?= $form->field($model, 'semestre')->radioList([1 => '1º semestre', 2 => '2° semestre']) ?>
    <?php endif;?>

    <?= $form->field($model, 'tipo_participacao')->dropDownList($model::TIPO_PARTICIPACAO) ?>

    <?= $form->field($model, 'carga_horaria')->widget(NumberControl::className(),[
        'readonly' => !$model->planoRelatorio->isEditable(),
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
            'max' => 20,
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
