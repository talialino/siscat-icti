<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitAtividadesAdministrativas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-atividades-administrativas-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true, 
        'validationUrl' => ['sispitatividadesadministrativas/validate',
            'id' => $model->isNewRecord ? $model->id_plano_relatorio : $model->id_atividades_administrativas, 'plano' => $model->isNewRecord
    ]]); ?>

    <?= $form->field($model, 'tipo_atividade')->dropDownList($model::TIPO_ATIVIDADE) ?>

    <?php if(Yii::$app->session->get('siscat_ano')->suplementar):?>
    <?= Html::activeHiddenInput($model,'semestre',['value' => 1]) ?>
    <?php else:?>
    <?= $form->field($model, 'semestre')->radioList([1 => '1º semestre', 2 => '2° semestre']) ?>
    <?php endif;?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'carga_horaria')->widget(NumberControl::className(),[
        'readonly' => !$model->planoRelatorio->isEditable(),
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
            'max' => 40,
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
