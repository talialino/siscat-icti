<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View /*/
/* @var $model app\modules\sisliga\models\SisligaRelatorio */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="sisliga-relatorio-form box">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'id_liga_academica',['options' => ['class' => 'col-md-12']])->widget(Select2::class, [
            'data' => ArrayHelper::map($searchLiga->listaLigas(), 'id_liga_academica', 'nome'),
            'options' => ['placeholder' => 'Selecione a liga'],
            'pluginOptions' => ['allowClear' =>true],
            'disabled' => !$model->isNewRecord,
    ]) ?>

    <?= $form->field($model, 'situacao_liga',['options' => ['class' => 'col-md-12']])->dropDownList($model::SITUACAO_LIGA) ?>

    <?= $form->field($model, 'atividades',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'prestacao_contas',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'consideracoes_finais',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'data_inicio',['options' => ['class' => 'col-md-4']])->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,]) ?>

    <?= $form->field($model, 'data_fim',['options' => ['class' => 'col-md-4']])->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,]) ?>

    <div class="form-group col-md-12">
        <?= Html::submitButton(Yii::t('app', 'Salvar'), ['class' => 'btn btn-success btn-lg pull-right']) ?>
    </div>
<div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>