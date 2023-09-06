<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Typeahead;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeFinanciamento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisape-financiamento-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'origem')->widget(Typeahead::classname(), [
        'options' => ['autocomplete' => 'off'],
        'defaultSuggestions' => $model::ORIGENS, 
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'local' => $model::ORIGENS,
                'limit' => count($model::ORIGENS)
            ]
        ]
    ]) ?>

    <?= $form->field($model, 'valor')->widget(MaskMoney::className(), [
        'pluginOptions' => [
            'prefix' => 'R$',
            'thousands' => '.',
            'decimal' => ',',
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salvar'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
