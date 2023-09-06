<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use app\assets\SisligaAsset;
use yii\data\ActiveDataProvider;
SisligaAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaLigaAcademica */

echo DetailView::widget([
    'model' => $model,
    'panel' => ['type' => 'primary', 'heading' => 'Dados da Liga Acadêmica'],
    'enableEditMode' => false,
    'attributes' => [
        ['label'=> 'Nome','attribute' => 'nome', 'format' => 'ntext'],
        ['label'=> 'Responsável', 'attribute' => 'id_pessoa', 'value' => $model->pessoa->nome],
        [
            'columns' => [
                ['attribute' => 'situacaoString', ],              
                
            ]
        ],
        'resumo:ntext',
        ['attribute' => 'area_conhecimento', 'format' => 'ntext'],
        ['attribute' => 'local_atuacao', 'format' => 'ntext'],
        ['attribute' => 'arquivo_solicitacao', 'format' => 'raw', 'value' => Html::a('Visualizar arquivo', Url::to(['/sisliga/sisligaligaacademica/visualizararquivo','id' => $model->id_liga_academica, 'tipoArquivo' => 1]), ['target' => '_blank'])],
        ['attribute' => 'arquivo_regimento', 'format' => 'raw', 'value' => Html::a('Visualizar arquivo', Url::to(['/sisliga/sisligaligaacademica/visualizararquivo','id' => $model->id_liga_academica, 'tipoArquivo' => 2]), ['target' => '_blank'])],
        ['label' => 'Equipe Executora','format' => 'raw', 'labelColOptions' => ['style'=>'width:12%'], 'value' => GridView::widget([
            'dataProvider' => new ActiveDataProvider(['query' => $model->getSisligaLigaIntegrantes(), 'pagination' => false,]),
            'summary' => '',
            'columns' => [
                'nome',
                'instituicao',
                'funcao',
                'carga_horaria',
            ],
        ])],
        'sessaoCongregacao',
    ],
]);
?>