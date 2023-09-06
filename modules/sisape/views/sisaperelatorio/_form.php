<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use app\modules\sisape\models\SisapeProjetoSearch;

/* @var $this yii\web\View /*/
/* @var $model app\modules\sisape\models\SisapeRelatorio */
/* @var $form yii\widgets\ActiveForm */

$displayJustificativa = $model->situacao_projeto > 2 ? 'display: none' : '';
?>

<div class="sisape-relatorio-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'id_projeto',['options' => ['class' => 'col-md-12']])->widget(Select2::className(), [
            'data' => ArrayHelper::map($searchProjeto->listaProjetos(), 'id_projeto', 'titulo'),
            'options' => ['placeholder' => 'Selecione o projeto'],
            'pluginOptions' => ['allowClear' =>true],
            'disabled' => !$model->isNewRecord,
    ]) ?>

    <?= $form->field($model, 'situacao_projeto',['options' => ['class' => 'col-md-12']])->dropDownList($model::SITUACAO_PROJETO, ['onclick' => "toogleJustificativa(this)"]) ?>

    <?= $form->field($model, 'justificativa',['options' => ['class' => 'col-md-12', 'id' => 'justificativa', 'style' => $displayJustificativa]])->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'alunos_orientados',['options' => ['class' => 'col-md-4']])->textInput() ?>

    <?= $form->field($model, 'resumos_publicados',['options' => ['class' => 'col-md-4']])->textInput() ?>

    <?= $form->field($model, 'artigos_publicados',['options' => ['class' => 'col-md-4']])->textInput() ?>

    <?= $form->field($model, 'artigos_aceitos',['options' => ['class' => 'col-md-4']])->textInput() ?>

    <?= $form->field($model, 'relatorio_agencia',['options' => ['class' => 'col-md-4']])->dropDownList([0 => 'Não', 1 => 'Sim']) ?>

    <?= $form->field($model, 'deposito_patente',['options' => ['class' => 'col-md-4']])->dropDownList([0 => 'Não', 1 => 'Sim']) ?>

    <?= $form->field($model, 'outros_indicadores',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'consideracoes_finais',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 6]) ?>

    <div class="form-group col-md-4">
        <?= Html::submitButton(Yii::t('app', 'Salvar'), ['class' => 'btn btn-success']) ?>
    </div>
<div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>