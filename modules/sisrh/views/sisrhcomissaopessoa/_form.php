<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\sisrh\models\SisrhPessoa;
use kartik\select2\Select2;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetorPessoa */
/* @var $form yii\widgets\ActiveForm */

$pessoas = ArrayHelper::map(SisrhPessoa::find()->orderby('nome')->all(),'id_pessoa','nome');
?>

<div class="sisrh-comissao-pessoa-form">
<div class='col-md-12 tabs-conteudo' style="border: 1px solid #e2e2e2">
    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
    <div class='col-md-7'>
    <?= $form->field($model, 'id_pessoa')->widget(Select2::className(), [
        'data' => $pessoas,
        'options' => ['placeholder' => 'Selecione a pessoa'],
        'pluginOptions' => ['allowClear' =>true]
    ]) ?>
    </div>
    <div class='col-md-5'>
    <?= $form->field($model, 'funcao')->dropDownList($model::FUNCOES,['prompt' => '']) ?>
    </div>
    <div class="form-group col-md-12">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
    <div class="clearfix">
</div>
