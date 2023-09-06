<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\number\NumberControl;
use app\modules\sisai\models\SisaiAluno;
use app\modules\sispit\models\SispitEnsinoOrientacao;

$alunos = ArrayHelper::map(SisaiAluno::find()->where(['ativo' => 1])->orderby('nome')->all(),'id_aluno','nome');

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitEnsinoOrientacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-ensino-orientacao-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 
        'validationUrl' => ['sispitensinoorientacao/validate',
            'id' => $model->isNewRecord ? $model->id_plano_relatorio : $model->id_ensino_orientacao, 'plano' => $model->isNewRecord
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

    <?= $form->field($model, 'projeto')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tipo_orientacao')->dropDownList(SispitEnsinoOrientacao::TIPO_ORIENTACAO) ?>

    <?= $form->field($model, 'carga_horaria')->widget(NumberControl::className(),[
        'readonly' => !$model->planoRelatorio->isEditable(),
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
            'max' => 20,
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
