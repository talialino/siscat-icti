<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\siscc\models\SisccComponenteCurricular;
use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhSetor;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siscc-programa-componente-curricular-search">
    <div class="row">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>
        <div class="col-md-2">
        <?= $form->field($model, 'id_semestre')->dropDownList(ArrayHelper::map(SisccSemestre::find()->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string')) ?>
        </div><div class="col-md-6">
        <?= $form->field($model, 'id_setor')->dropDownList(ArrayHelper::map($colegiados,'id_setor','nome')) ?>
        </div><div class="col-md-4">
        <?= $form->field($model, 'situacao')->dropDownList($model::SITUACAO,['prompt' => '']) ?>
        </div><div class="col-md-12">
        <?= $form->field($model, 'id_componente_curricular')->widget(Select2::className(),[
            'data' => ArrayHelper::map(SisccComponenteCurricular::find()->where(['ativo' => 1])->orderby('nome')->all(),'id_componente_curricular', 'codigoNome'),
            'options' => ['placeholder' => 'Selecione o componente'],
            'pluginOptions' => ['allowClear' =>true]
        ]) ?>
        </div><div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
