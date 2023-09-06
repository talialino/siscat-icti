<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\siscc\models\SisccComponenteCurricular;
use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhSetor;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricular */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siscc-programa-componente-curricular-form">
    <div class='col-md-12 conteudoFormulario'>
        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
        
        <div class='col-md-12'>
        <?= $form->field($model, 'id_componente_curricular')->widget(Select2::class,[
            'data' => ArrayHelper::map(SisccComponenteCurricular::find()->where(['ativo' => 1])->orderby('nome')->all(),'id_componente_curricular', 'codigoNome'),
            'options' => ['placeholder' => 'Selecione o componente'],
            'pluginOptions' => ['allowClear' =>true]
        ]) ?>
        </div><div class='col-md-9'>
        <?= $form->field($model, 'id_setor')->dropDownList(ArrayHelper::map(SisrhSetor::find()->where(['eh_colegiado' => 1])->all(),'id_setor','nome'),['prompt' => '']) ?>
        </div><div class='col-md-3'>
        <?= $form->field($model, 'id_semestre')->dropDownList(ArrayHelper::map(SisccSemestre::find()->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string'),['prompt' => '']) ?>
        </div>
        <div class="form-group col-md-12">
            <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="clearfix"></div>
</div>
