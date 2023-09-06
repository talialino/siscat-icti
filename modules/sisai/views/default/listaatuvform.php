<?php

use app\assets\SisaiAsset;
use app\modules\siscc\models\SisccSemestre;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
SisaiAsset::register($this);


/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Lista de alunos para ATUV');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-lista-aluno">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <div class="sisrh-cargo-form">

        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
        <div class='box box-primary' style="padding: 20px; margin: 0 auto 20px; max-width: 500px;">
            <div>
                <?= $form->field($model, 'id_semestre')->dropDownList(ArrayHelper::map(SisccSemestre::find()->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string')) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="clearfix"></div>
    </div>