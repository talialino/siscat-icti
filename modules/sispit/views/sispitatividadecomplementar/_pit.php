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
        'action' => ['sispitatividadecomplementar/update', 'id' => $model->id_plano_relatorio, 'scenario' => SispitAtividadeComplementar::SCENARIO_ATIVIDADE_PIT]]);?>

    <div class="<?= $suplementar ? 'col-lg-6 form-group' : 'col-lg-3 form-group'?>">
    <?= $form->field($model, 'ch_graduacao_sem1_pit')->widget(NumberControl::className(),[
        'readonly' => !$model->planoRelatorio->isPitEditable(),
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
        ],
    ]) ?>
    </div>

    <div class="<?= $suplementar ? 'col-lg-6 form-group' : 'col-lg-3 form-group'?>">
    <?= $form->field($model, 'ch_pos_sem1_pit')->widget(NumberControl::className(),[
        'readonly' => !$model->planoRelatorio->isPitEditable(),
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
        ]
    ]) ?>
    </div>
    <?php if(!$suplementar):?>
        <div class="col-lg-3 form-group">
        <?= $form->field($model, 'ch_graduacao_sem2_pit')->widget(NumberControl::className(),[
            'readonly' => !$model->planoRelatorio->isPitEditable(),
            'maskedInputOptions' => [
                'digits' => 0,
                'allowMinus' => false,
            ]
        ]) ?>
        </div>

        <div class="col-lg-3 form-group">
        <?= $form->field($model, 'ch_pos_sem2_pit')->widget(NumberControl::className(),[
            'readonly' => !$model->planoRelatorio->isPitEditable(),
            'maskedInputOptions' => [
                'digits' => 0,
                'allowMinus' => false,
            ]
        ]) ?>
        </div>
        <?php endif;?>
    <div class="form-group" style="clear: both;">
        <?php if($model->planoRelatorio->isPitEditable()) echo Html::submitButton('Salvar', ['class' => 'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
