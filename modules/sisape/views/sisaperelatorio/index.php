<?php

use yii\helpers\Html;
use yii\helpers\URl;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\assets\SisapeAsset;
use yii\data\ActiveDataProvider;
use app\modules\sisape\models\SisapeProjetoSearch;
SisapeAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisape\models\SisapeRelatorioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Meus Relatorios');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-relatorio-index">

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
                Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => Yii::t('app', 'Adicionar Sisape Relatorio'), 'class' => 'btn btn-success', ]),
            ],
        ],
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'id_projeto',
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true,],
                ],
                'filterInputOptions' => ['placeholder' => 'Todos os projetos'],
                'filter' => ArrayHelper::map($searchProjeto->listaProjetos(60), 'id_projeto', 'titulo'), 
                'value' => function ($model, $key, $index, $widget){
                    return $model->projetoTitulo;
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
           /* ['label' => 'Pareceres','width' => '40%','value' => function($data){return GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $data->getSisapePareceres(), 'sort' => false]),
                'summary' => '',
                'responsiveWrap' => false,
                'columns' => [
                    ['attribute' =>'parecer','width' => '100%'],
                ],
            ]);}, 'format' => 'raw'],*/
            ['label' => 'Congregação', 'attribute' => 'data_homologacao_congregacao','width' => '10%',
                'filter' => false, 'value' => function($data){
                    if($data->situacao == 12)
                        return $data->sessaoCongregacao.' '.
                            Yii::$app->formatter->format($data->data_homologacao_congregacao,'date');
                    return false;
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'delete' => function ($model){
                        return $model->situacao == 13;
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
