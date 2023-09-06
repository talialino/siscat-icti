<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\number\NumberControl;
use yii\helpers\ArrayHelper;
use app\modules\siscc\models\SisccComponenteCurricular;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitEnsinoComponente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-ensino-componente-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'id_componente_curricular')->widget(Select2::className(),[
            'data' => ArrayHelper::map(SisccComponenteCurricular::find()->where(['ativo' => 1])->orderby('nome')->all(),'id_componente_curricular', 'codigoNome'),
            'options' => ['placeholder' => 'Selecione o componente'],
            'pluginOptions' => ['allowClear' =>true]
        ]) ?>

    <?= $form->field($model, 'nivel_graduacao')->radioList(['Graduação', 'Pós-graduação']) ?>

    <?php if(Yii::$app->session->get('siscat_ano')->suplementar):?>

        <?= Html::activeHiddenInput($model,'semestre',['value' => 1]) ?>

    <?php else:?>

        <?= $form->field($model, 'semestre')->radioList([1 => '1º semestre', 2 => '2° semestre']) ?>

    <?php endif;?>

    <?= $form->field($model, 'ch_teorica')->widget(NumberControl::className(),[
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
        ]
    ]) ?>

    <?= $form->field($model, 'ch_pratica')->widget(NumberControl::className(),[
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
        ]
    ]) ?>

    <?= $form->field($model, 'ch_estagio')->widget(NumberControl::className(),[
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
