<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\sisrh\models\SisrhSetor;
use app\modules\sisrh\models\SisrhSetorPessoa;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetorMembroAutomatico */
/* @var $form yii\widgets\ActiveForm */

$setores = ArrayHelper::map(SisrhSetor::find()->all(),'id_setor','nome');

$funcoes = SisrhSetorPessoa::FUNCOES;

?>

<div class="sisrh-setor-membro-automatico-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'id_setor_origem')->widget(Select2::className(), [
        'data' => $setores,
        'options' => ['placeholder' => 'Selecione o setor'],
        'pluginOptions' => ['allowClear' =>true]
    ]) ?>

    <?= $form->field($model, 'funcao_origem')->dropDownList($funcoes, ['prompt' => 'Selecione a função']) ?>

    <?= $form->field($model, 'id_setor_destino')->widget(Select2::className(), [
        'data' => $setores,
        'options' => ['placeholder' => 'Selecione o setor'],
        'pluginOptions' => ['allowClear' =>true]
    ]) ?>

    <?= $form->field($model, 'funcao_destino')->dropDownList($funcoes, ['prompt' => 'Selecione a função']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salvar'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
