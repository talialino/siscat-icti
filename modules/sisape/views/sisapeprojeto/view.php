<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use app\assets\SisapeAsset;
use yii\data\ActiveDataProvider;
SisapeAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeProjeto */

$this->title = 'Visualizar Projeto';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = ['label' => 'Meus Projetos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-projeto-view">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1> 
    <?php if(Yii::$app->user->can('sisapeAdministrar')):?>
        <p>
            <?= Html::a('Atualizar', ['update', 'id' => $model->id_projeto], ['class' => 'btn btn-warning pull-right']) ?>
        </p>
    <?php endif;?>
    <div class="clearfix" style="margin-bottom:10px"></div>

    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => $this->title],
        'enableEditMode' => false,
        'attributes' => [
            'numero',
            'titulo:ntext',
            'tipoProjeto',
            ['attribute' => 'id_pessoa', 'value' => $model->pessoa->nome],
            ['attribute' => 'tipoExtensao', 'visible' => $model->tipo_projeto == $model::EXTENSAO],
            [
                'columns' => [
                    'data_inicio:date',
                    'data_fim:date',
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'situacaoString',  'valueColOptions'=>['style'=>'width:30%']],
                    'disponivel_site:boolean',                   
                    
                ]
            ],
            'resumo:ntext',
            ['attribute' => 'area_atuacao', 'format' => 'ntext'],
            ['attribute' => 'local_execucao', 'format' => 'ntext', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['attribute' => 'parcerias', 'format' => 'ntext', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['attribute' => 'infraestrutura', 'format' => 'ntext', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['attribute' => 'submetido_etica', 'format' => 'boolean', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['attribute' => 'introducao', 'format' => 'ntext', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['attribute' => 'justificativa', 'format' => 'ntext', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['attribute' => 'objetivos', 'format' => 'ntext', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['attribute' => 'metodologia', 'format' => 'ntext', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['attribute' => 'resultados_esperados', 'format' => 'ntext', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['attribute' => 'orcamento', 'format' => 'ntext', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['attribute' => 'referencias', 'format' => 'ntext', 'visible' => $model->tipo_projeto == $model::PESQUISA],
            ['label' => 'Financiamento','format' => 'raw', 'labelColOptions' => ['style'=>'width:12%'], 'value' => GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $model->getSisapeFinanciamentos(), 'pagination' => false,]),
                'summary' => '',
                'columns' => [
                    'origem',
                    'valor:currency',
                ],
            ])],
            ['label' => 'Equipe Executora','format' => 'raw', 'labelColOptions' => ['style'=>'width:12%'], 'value' => GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $model->getSisapeProjetoIntegrantes(), 'pagination' => false,]),
                'summary' => '',
                'columns' => [
                    'nome',
                    'vinculoString',
                    'funcao',
                    'carga_horaria',
                ],
            ])],
            ['label' => 'Cronograma','format' => 'raw', 'labelColOptions' => ['style'=>'width:12%'], 'value' => GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $model->getSisapeAtividades(), 'pagination' => false,]),
                'summary' => '',
                'columns' => [
                    'descricao_atividade',
                    'data_inicio:date',
                    'data_fim:date',
                ],
            ])]
        ],
    ]) ?>

    <?=Html::a('Salvar em PDF', ['pdf', 'id' => $model->id_projeto],
            ['class' => 'btn btn-primary']
    )?>
</div>
