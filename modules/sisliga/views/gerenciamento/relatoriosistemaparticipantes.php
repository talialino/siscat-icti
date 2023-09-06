<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\sisliga\models\SisligaLigaAcademica;
use app\modules\sisliga\models\IntegranteForm;
use kartik\grid\GridView;
use app\assets\SisligaAsset;

SisligaAsset::register($this);

$this->title = 'Relatório de Sistema - Participantes';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisliga')), 'url' => ['/'.strtolower(Yii::t('app', 'sisliga'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisliga-relatorio-sistema-participantes">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <div class="sisliga-relatorio-form">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>
            
            <?= $form->field($model, 'tipo_integrante',['options' => ['class' => 'col-md-3']])->dropDownList(IntegranteForm::TIPOS, ['prompt' => 'Todos']) ?>
            
            <?= $form->field($model, 'ano',['options' => ['class' => 'col-md-3']])->input('number') ?>

            <?= $form->field($model, 'area_conhecimento',['options' => ['class' => 'col-md-6']])->input('text') ?>

            <?= $form->field($model, 'situacao',['options' => ['class' => 'col-md-6']])->dropDownList(SisligaLigaAcademica::SITUACAO, ['prompt' => 'Todas as situações']) ?>
            
            <div class='form-group col-md-12' style='padding-top: 20px;'>
                <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
            </div>

            <div class="clearfix"></div>
            
        <?php ActiveForm::end(); ?>
    </div>
    <div class="sisliga-relatorio-sistema-participantes">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'pjax' => true,
                'panel' => [
                    'type' => 'primary',
                    'after' => false,
                ],
                'toolbar' =>  [
                    '{export}',
                    '{toggleData}',
                ],
                'columns' => [
                    'nome',
                    'ligaAcademica.nome',
                    'funcao',
                    'carga_horaria',
                ],
            ]); ?>
        </div>
    </div>
</div>