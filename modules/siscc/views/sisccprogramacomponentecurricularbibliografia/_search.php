<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\siscc\models\SisccSemestre;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografiaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin([

        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="col-sm-8">
    <?= $form->field($model, 'programaComponenteCurricular.id_semestre')->label('Selecione o semestre')->dropDownList(ArrayHelper::map(SisccSemestre::find()->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string')) ?>
    </div>
    <div style="margin-top:23px" class="form-group col-sm-4">
        <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<div class="clearfix"></div>