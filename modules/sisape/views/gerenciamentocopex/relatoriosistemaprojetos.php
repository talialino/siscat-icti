<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\sisape\models\SisapeProjeto;
use app\modules\sisape\models\SisapeFinanciamento;
use kartik\grid\GridView;
use app\assets\SisapeAsset;

SisapeAsset::register($this);

$this->title = 'Relatório de Sistema - Projetos';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-relatorio-sistema-projetos">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <div class="sisape-relatorio-form">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>
            <?= $form->field($model, 'tipo_projeto',['options' => ['class' => 'col-md-4']])->dropDownList(SisapeProjeto::TIPO_PROJETO, ['prompt' => 'Todos']) ?>
            
            <?= $form->field($model, 'origem_financiamento',['options' => ['class' => 'col-md-4']])->dropDownList(SisapeFinanciamento::ORIGENS, ['prompt' => 'Todas']) ?>
            
            <?= $form->field($model, 'ano',['options' => ['class' => 'col-md-4']])->input('number') ?>

            <?= $form->field($model, 'area_atuacao',['options' => ['class' => 'col-md-6']])->input('text') ?>

            <?= $form->field($model, 'situacao',['options' => ['class' => 'col-md-6']])->dropDownList(SisapeProjeto::SITUACAO, ['prompt' => 'Todas as situações']) ?>
            
            <div class='form-group col-md-12' style='padding-top: 20px;'>
                <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
            </div>

            <div class="clearfix"></div>
            
        <?php ActiveForm::end(); ?>
    </div>
    <div class="sisape-relatorio-sistema-projetos">
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
                    'titulo:ntext',
                    'pessoa.nome',
                    [
                        'attribute' =>'tipo_projeto',        
                        'value' => function ($model, $key, $index, $widget){
                            return $model->tipoProjeto;
                        }
                    ],
                    [
                        'attribute' => 'situacao',
                        'value' => function ($model, $key, $index, $widget){
                            return $model->situacaoString;
                        }
                    ],
                    'data_inicio:date',
                    'data_fim:date',
                ],
            ]); ?>
        </div>
    </div>
</div>