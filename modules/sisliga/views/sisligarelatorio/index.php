<?php

use yii\helpers\Html;
use yii\helpers\URl;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\assets\SisligaAsset;
use yii\data\ActiveDataProvider;
SisligaAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisliga\models\SisligaRelatorioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Meus Relatórios');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisliga')), 'url' => ['/'.strtolower(Yii::t('app', 'sisliga'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisliga-relatorio-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'primary',
            'after' =>false,
        ],
        'toolbar' =>  [
            ['content' => 
                Html::a('<i class="glyphicon glyphicon-plus"></i> Novo Relatório',['create'], ['title' => Yii::t('app', 'Adicionar Relatório'), 'class' => 'btn btn-success', ]),
            ],
        ],
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'id_liga_academica',
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true,],
                ],
                'filterInputOptions' => ['placeholder' => 'Todos as ligas'],
                'filter' => ArrayHelper::map($searchLiga->listaLigas(60), 'id_liga_academica', 'nome'), 
                'value' => function ($model, $key, $index, $widget){
                    return $model->ligaNome;
                }
            ],
            [
                'attribute' => 'data_relatorio',
                'filter' => false,
                'format' => 'date',
            ],
            [
                'attribute' => 'situacao',
                'filter' => $searchModel::SITUACAO,
                'value' => function ($model, $key, $index, $widget){
                    return $model->situacaoString;
                }
            ],
            ['label' => 'Pareceres','width' => '40%','value' => function($data){return GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $data->getSisligaPareceres(), 'sort' => false]),
                'summary' => '',
                'responsiveWrap' => false,
                'showHeader'=> false,
                'columns' => [
                    ['attribute' =>'parecer','width' => '100%'],
                ],
            ]);}, 'format' => 'raw'],
            ['label' => 'Congregação', 'attribute' => 'data_homologacao_congregacao','width' => '10%',
                'filter' => false, 'value' => function($data){
                    if($data->situacao == 7)
                        return $data->sessaoCongregacao.' '.
                            Yii::$app->formatter->format($data->data_homologacao_congregacao,'date');
                    return false;
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
             'contentOptions' => ['style' => 'width:80px;'],
                'visibleButtons' => [
                    'delete' => function ($model){
                        return $model->situacao < 1;
                    },
                    'update' => function($model){
                        return $model->isEditable();
                    }
                ],
            ],
        ],
    ]); ?>
    </div>
</div>
