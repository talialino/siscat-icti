<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use app\modules\sisrh\models\SisrhCargo;
use app\modules\sisrh\models\SisrhCategoria;
use app\modules\sisrh\models\SisrhOcorrencia;
use app\modules\sisrh\models\SisrhSetor;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhPessoaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisrh-pessoa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['ocorrencias'],
        'method' => 'get',
    ]); ?>
    <fieldset class="form-group">
    <legend>Filtrar Ocorrências</legend>
    <div class="col-md-3">
    <?= $form->field($model, 'pessoa.nome',['inputOptions' =>['class' => 'form-control','autofocus' => '1']]) ?>
    </div>
    <div class="col-md-3">
    <?= $form->field($model, 'cargo.id_cargo')->label('Cargo')->dropDownList(ArrayHelper::map(SisrhCargo::find()->orderBy('descricao')->all(),'id_cargo','descricao'),['prompt' => 'Todos']) ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'cargo.id_categoria')->dropDownList(ArrayHelper::map(SisrhCategoria::find()->orderBy('id_categoria')->all(),'id_categoria','nome'),['prompt' => 'Todos']) ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'setor.id_setor')->label('Setor')->dropDownList(ArrayHelper::map(SisrhSetor::find()->orderBy('nome')->all(),'id_setor','nome'),['prompt' => 'Todos']) ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'id_ocorrencia')->dropDownList(ArrayHelper::map(SisrhOcorrencia::find()->orderBy('justificativa')->all(),'id_ocorrencia','justificativa'),['prompt' => 'Todos']) ?>
    </div>
    <div style="padding-left:15px" class="form-group">
        <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app','Reset'), ['class' => 'btn btn-default']) ?>
    </div>
    </fieldset>

    <?php ActiveForm::end(); ?>

</div>
