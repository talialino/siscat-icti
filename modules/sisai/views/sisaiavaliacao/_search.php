<?php

use app\modules\sisai\models\SisaiAluno;
use app\modules\sisai\models\SisaiAvaliacao;
use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhPessoa;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiAvaliacaoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisai-avaliacao-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="col-sm-2">
        <?= $form->field($model, 'id_semestre')->dropDownList(ArrayHelper::map(SisccSemestre::find()->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string'),['prompt' => '']) ?>
    </div>
    <div class="col-sm-4">
    <?= $form->field($model, 'id_aluno')->widget(Select2::class,[
        'data' => ArrayHelper::map(SisaiAluno::find()->select(['id_aluno','nome'])->orderBy('nome')->all(),'id_aluno','nome'),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' =>true]
        ]
    ) ?>
    </div>
    <div class="col-sm-4">
    <?= $form->field($model, 'id_pessoa')->widget(Select2::class,[
        'data' => ArrayHelper::map(SisrhPessoa::find()->select(['id_pessoa','nome'])->orderBy('nome')->all(),'id_pessoa','nome'),
        'options' => ['placeholder' => ''],
        'pluginOptions' => ['allowClear' =>true]
        ]
    ) ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'tipo_avaliacao')->label('Tipo')->dropDownList(SisaiAvaliacao::TIPO_AVALIACAO,['prompt' => '']) ?>
    </div>
    <div class="col-sm-1">
        <?= $form->field($model, 'situacao')->label('Situação Concluída')->checkbox(['value' => 99],false) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
