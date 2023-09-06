<?php

use app\modules\sisai\models\SisaiAluno;
use app\modules\siscc\models\SisccSemestre;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\sisrh\models\SisrhSetor;
use dektrium\user\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiAluno */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisai-aluno-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class='col-md-12'>
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
        <?= $form->field($model, 'matricula')->textInput() ?>
        </div>
        <div class='col-md-6'>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-8'>
        <?= $form->field($model, 'id_setor')->dropDownList(ArrayHelper::map(SisrhSetor::find()->where(['eh_colegiado' => 1])->all(), 'id_setor','nome')) ?>
        </div>
        <div class='col-md-4'>
        <?= $form->field($model, 'ativo')->radioList([1 => Yii::t('app','Ativo'), 0 => Yii::t('app','Inativo')],['itemOptions'=>['labelOptions' => ['style' => 'margin-right: 10px;']]]) ?>
        </div>
        <div class='col-md-6'>
        <?= $form->field($model, 'id_semestre')->dropDownList(ArrayHelper::map(SisccSemestre::find()->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string')) ?>
        </div>
        <div class='col-md-6'>
        <?= $form->field($model, 'nivel_escolaridade')->dropDownList(SisaiAluno::ESCOLARIDADE) ?>
        </div>
        <div class='col-md-4'>
            <?= $form->field(isset($model->user) ? $model->user : new User, 'username')->textInput()?>
        </div>
        <div class='col-md-12'>
            <div class="form-group">
                <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
