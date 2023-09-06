<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\sisai\models\SisaiAluno;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitOrientacaoAcademica */
/* @var $form yii\widgets\ActiveForm */

$alunos = ArrayHelper::map(SisaiAluno::find()->where(['ativo' => 1])->orderby('nome')->all(),'id_aluno','nome');
?>

<div class="sispit-orientacao-academica-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 
        'validationUrl' => ['sispitorientacaoacademica/validate',
            'id' => $model->isNewRecord ? $model->id_plano_relatorio : $model->id_orientacao_academica, 'plano' => $model->isNewRecord
        ]]); ?>

    <?php if(Yii::$app->session->get('siscat_ano')->suplementar):?>

        <?= Html::activeHiddenInput($model,'semestre',['value' => 1]) ?>

    <?php else:?>

        <?= $form->field($model, 'semestre')->radioList([1 => '1º semestre', 2 => '2° semestre']) ?>

    <?php endif;?>

    <?= $form->field($model, 'id_aluno')->widget(Select2::className(), [
        'data' => $alunos,
        'options' => ['placeholder' => 'Selecione o discente'],
        'pluginOptions' => ['allowClear' =>true]
    ])?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
