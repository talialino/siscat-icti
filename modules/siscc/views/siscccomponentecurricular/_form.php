<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use unclead\multipleinput\MultipleInput;
use app\modules\sisrh\models\SisrhSetor;
use app\modules\siscc\models\SisccComponenteCurricular;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccComponenteCurricular */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="siscc-componente-curricular-form">

        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
        <div class="row">
            <div class='col-md-9'>
                <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'codigo_componente')->textInput(['maxlength' => true]) ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'ch_teorica')->textInput() ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'ch_pratica')->textInput() ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'ch_estagio')->textInput() ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'ch_extensao')->textInput() ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'modulo_teoria')->textInput() ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'modulo_pratica')->textInput() ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'modulo_estagio')->textInput() ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'modulo_extensao')->textInput() ?>
            </div>
            <div class='col-md-6'>
                <?= $form->field($model, 'modalidade')->dropDownList($model::MODALIDADE) ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'anual')->dropDownList([0 => 'Não', 1 => 'Sim']) ?>
            </div>
            <div class='col-md-3'>
                <?= $form->field($model, 'ativo')->dropDownList([1 => 'Sim', 0 => 'Não']) ?>
            </div>
            <div class='col-md-12'>
                <?= $form->field($model, 'ementa')->textarea(['rows' => 6]) ?>
            </div>
            <div class='col-md-12'>
            <?= $form->field($model, 'prerequisitosInput')->widget(MultipleInput::class,[
                'columns' => [
                    [
                        'name' => 'colegiado',
                        'type' => 'dropDownList',
                        'title' => 'Colegiado',
                        'items' => ArrayHelper::map(SisrhSetor::find()->where(['eh_colegiado' => 1])->all(), 'codigo', 'nome'),
                        'options' => [
                            'prompt' => ''
                        ]
                    ],
                    [
                        'name' => 'componente',
                        'title' => 'Pré-requisito',
                    ]
                ]
            ]) ?>
            </div>
            <div class='col-md-12'>
                <div class="form-group">
                    <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    <div class="clearfix"></div>
</div>
