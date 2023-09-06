<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\sisape\models\SisapeProjeto;
use app\modules\sisape\models\ProjetoIntegranteForm;
use app\modules\sisape\models\SisapeProjetoIntegrante;
use kartik\grid\GridView;
use app\assets\SisapeAsset;

SisapeAsset::register($this);

$this->title = 'Relatório de Sistema - Participantes';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-relatorio-sistema-participantes">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <div class="sisape-relatorio-form">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>
            <?= $form->field($model, 'tipo_projeto',['options' => ['class' => 'col-md-3']])->dropDownList(SisapeProjeto::TIPO_PROJETO, ['prompt' => 'Todos']) ?>
            
            <?= $form->field($model, 'tipo_integrante',['options' => ['class' => 'col-md-3']])->dropDownList(ProjetoIntegranteForm::TIPOS, ['prompt' => 'Todos']) ?>

            <?= $form->field($model, 'vinculo_integrante',['options' => ['class' => 'col-md-3']])->dropDownList(SisapeProjetoIntegrante::VINCULOS, ['prompt' => 'Todos']) ?>
            
            <?= $form->field($model, 'ano',['options' => ['class' => 'col-md-3']])->input('number') ?>

            <?= $form->field($model, 'area_atuacao',['options' => ['class' => 'col-md-6']])->input('text') ?>

            <?= $form->field($model, 'situacao',['options' => ['class' => 'col-md-6']])->dropDownList(array_slice(SisapeProjeto::SITUACAO, 0, 15, true), ['prompt' => 'Todas as situações']) ?>
            
            <div class='form-group col-md-12' style='padding-top: 20px;'>
                <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
            </div>

            <div class="clearfix"></div>
            
        <?php ActiveForm::end(); ?>
    </div>
    <div class="sisape-relatorio-sistema-participantes">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'pjax' => true,
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
                    'projeto.titulo',
                    'funcao',
                    'carga_horaria',
                    'vinculoString',
                ],
            ]); ?>
        </div>
    </div>
</div>