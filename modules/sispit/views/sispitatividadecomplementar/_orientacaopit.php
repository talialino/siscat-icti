<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\number\NumberControl;
use app\modules\sispit\models\SispitAtividadeComplementar;

$suplementar = Yii::$app->session->get('siscat_ano')->suplementar;
/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitAtividadeComplementar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-atividade-complementar-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true, 
        'validationUrl' => ['sispitatividadecomplementar/validate', 'id' => $model->id_plano_relatorio],
        'action' => ['sispitatividadecomplementar/update', 'id' => $model->id_plano_relatorio, 'scenario' => SispitAtividadeComplementar::SCENARIO_ORIENTACAO_PIT]]); ?>
        
    <div class="<?=$suplementar ? 'col-sm-8' :'col-sm-4' ?>">
    <?= $form->field($model, 'ch_orientacao_academica_sem1_pit')->widget(NumberControl::className(),[
        'readonly' => !$model->planoRelatorio->isPitEditable(),
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
            'max' => 20,
        ]
    ]) ?>
    </div>
    <?php if(!$suplementar):?>
    <div class="col-sm-4">
    <?= $form->field($model, 'ch_orientacao_academica_sem2_pit')->widget(NumberControl::className(),[
        'readonly' => !$model->planoRelatorio->isPitEditable(),
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
            'max' => 20,
        ]
    ]) ?>
    </div>
        <?php endif;?>
    <div class="form-group col-sm-offset-2 col-sm-2">
        <?= $model->planoRelatorio->isPitEditable() ? Html::submitButton('Salvar', ['class' => 'btn btn-success']) : '' ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
