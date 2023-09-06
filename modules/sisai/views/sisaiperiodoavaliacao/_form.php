<?php

use app\modules\sisai\models\SisaiQuestionario;
use app\modules\siscc\models\SisccComponenteCurricular;
use app\modules\siscc\models\SisccProgramaComponenteCurricularPessoaSearch;
use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhSetor;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiPeriodoAvaliacao */
/* @var $form yii\widgets\ActiveForm */

$siscatAdmin = Yii::$app->user->can('siscatAdministrar');

$componentes = ArrayHelper::map(SisccComponenteCurricular::find()->all(),'id_componente_curricular','codigoNome');
?>

<div class="sisai-periodo-avaliacao-form">
    <div class="row">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
    <div class="col-sm-4">
    <?= $form->field($model, 'id_semestre')->dropDownList(ArrayHelper::map(SisccSemestre::find()->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string'),['prompt' => '']) ?>
    </div>
    <div class="col-sm-4">
    <?= $form->field($model, 'data_inicio')->widget(DateControl::class, [
        'type'=>DateControl::FORMAT_DATETIME,
        'ajaxConversion'=>false,
    ]) ?>
    </div>
    <div class="col-sm-4">
    <?= $form->field($model, 'data_fim')->widget(DateControl::class, [
        'type'=>DateControl::FORMAT_DATETIME,
        'ajaxConversion'=>false,
    ])?>
    </div>
    <div class="col-sm-12">
    <?php if($siscatAdmin):?>

        <?= $form->field($model, 'questionarios')->widget(MultipleInput::class,[
            
            'columns' => [
                [
                    'name'  => 'questionarios',
                    'type'  => 'dropDownList',
                    
                    'items' => ArrayHelper::map( SisaiQuestionario::find()->asArray()->all(),'id_questionario','titulo'),
                ],
            ]

        ]) ?>

    <?php endif;?>
    </div>
    <div class="col-sm-12">
    <?= $form->field($model, 'componentes_estagio')->widget(MultipleInput::class,[
            
            'columns' => [
                [
                    'name'  => 'componentes_estagio',
                    'type'  => 'dropDownList',
                    
                    'items' => $componentes,
                ],
            ]

        ]) ?>

        <?= $form->field($model, 'componentes_online')->widget(MultipleInput::class,[
            
            'columns' => [
                [
                    'name'  => 'colegiado',
                    'type'  => 'dropDownList',
                    'title' => 'Colegiado',
                    'items' => ArrayHelper::map(SisrhSetor::find()->where(['eh_colegiado' => 1])->all(),'id_setor','colegiado'),
                ],
                [
                    'name'  => 'componente',
                    'title' => 'Componente',
                    'type'  => Select2::class,
                    'options' => [
                        'data' => $componentes,
                        'options' => ['placeholder' => 'Selecione o componentes ministrado de forma online',],
                    ],
                ],
                [
                    'name'  => 'docente',
                    'title' => 'Docente',
                    'type'  => Select2::class,
                    'options' => [
                        'data' => ArrayHelper::map(SisrhPessoa::find()->orderBy('nome')->all(),'id_pessoa','nome'),
                        'options' => ['placeholder' => 'Selecione o(a) docente',],
                    ],
                ],
            ],
        ]) ?>
    </div>
    <div class="form-group col-sm-12">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-lg btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
        </div>
</div>
