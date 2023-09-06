<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use app\modules\sisape\models\SisapeProjetoIntegrante;
use app\modules\sisape\models\ProjetoIntegranteForm;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisai\models\SisaiAluno;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeProjetoIntegrante */
/* @var $form yii\widgets\ActiveForm */

$pessoas = ArrayHelper::map(SisrhPessoa::getAtivos('id_pessoa,nome'),'id_pessoa','nome');
$alunos = ArrayHelper::map(SisaiAluno::getAtivos('id_aluno, nome, matricula'),'id_aluno','matriculaNome');
$ocultarServidor = $ocultarDiscente = $ocultarIntegranteExterno = 'display: none';
switch($model->tipoIntegrante)
{
    case 1:
        $ocultarDiscente = null;
    break;
    case 2:
        $ocultarServidor = null;
    break;
    case 3:
        $ocultarIntegranteExterno = null;
}


?>
<div class="sisape-projeto-integrante-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
        'validationUrl' => ['validate', 'id' => $model->id_projeto],
    ]); ?>
    <div class="col-sm-12">
    <?php if(!$model->tipoIntegrante):?>
        <?= $form->field($model, 'tipoIntegrante')->dropDownList(ProjetoIntegranteForm::TIPOS, ['prompt' => '', 'onclick' => "escolherTipoIntegrante(this)"]) ?>
    <?php endif;?>
    </div>
    <div class="col-sm-12">
    <?= $form->field($model, 'id_pessoa',['options' => ['id' => 'id_pessoa','style' => $ocultarServidor]])->widget(Select2::className(), [
        'data' => $pessoas,
        'options' => ['placeholder' => 'Selecione o servidor'],
        'pluginOptions' => ['allowClear' =>true]
    ]) ?>
    </div>
    <div class="col-sm-12">
    <?= $form->field($model, 'id_aluno',['options' => ['id' => 'id_aluno','style' => $ocultarDiscente]])->widget(Select2::className(), [
        'data' => $alunos,
        'options' => ['placeholder' => 'Selecione a discente'],
        'pluginOptions' => ['allowClear' =>true]
    ]) ?>
    </div>
    <div class="col-sm-12">
    <?= $form->field($model, 'nome',['options' => ['id' => 'nome','style' => $ocultarIntegranteExterno]])->textInput() ?>
    </div>
    <div class="col-sm-6">
    <?= $form->field($model, 'email',['options' => ['id' => 'email','style' => $ocultarIntegranteExterno]])->textInput() ?>
    </div>
    <div class="col-sm-6">
    <?= $form->field($model, 'telefone',['options' => ['id' => 'telefone','style' => $ocultarIntegranteExterno]])->textInput() ?>
    </div>
    <div class="col-sm-5">
    <?= $form->field($model, 'funcao')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
    <?= $form->field($model, 'carga_horaria')->textInput()->label('CH Semanal') ?>
    </div>
    <div class="col-sm-4">
    <?= $form->field($model, 'vinculo',['options' => ['id' => 'vinculo','style' => $model->tipoIntegrante == 2 ? 'display: none' : null]])->dropDownList(SisapeProjetoIntegrante::VINCULOS, ['prompt' => '']) ?>
    </div>
    <div class="col-sm-12">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salvar'), ['class' => 'btn btn-success']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="clearfix"></div>

</div>
