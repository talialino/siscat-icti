<?php

use yii\helpers\Html;
use yii\helpers\URl;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use app\assets\SisapeAsset;
SisapeAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisape\models\SisapeProjetoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Meus Projetos';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-projeto-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  [
            ['content' => 
                Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => Yii::t('app', 'Adicionar Sisape Projeto'), 'class' => 'btn btn-success', ]),
            ],
        ],
        'filterModel' => $searchModel,
        'columns' => [
            'titulo:ntext',
            [
                'attribute' =>'tipo_projeto',
                'filter' => $searchModel::TIPO_PROJETO,
                'value' => function ($model, $key, $index, $widget){
                    return $model->tipoProjeto;
                }
            ],
            [
                'attribute' => 'situacao',
                'filter' => $searchModel::SITUACAO,
                'value' => function ($model, $key, $index, $widget){
                    return $model->situacaoString;
                }
            ],
            /*['label' => 'Pareceres','width' => '40%','value' => function($data){return GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $data->getSisapePareceres(), 'sort' => false]),
                'summary' => '',
                'responsiveWrap' => false,
                'columns' => [
                    ['attribute' =>'parecer','width' => '100%'],
                ],
            ]);}, 'format' => 'raw'],*/
            ['label' => 'Congregação', 'attribute' => 'data_homologacao_congregacao','width' => '10%',
                'filter' => false, 'value' => function($data){
                    if($data->situacao == 12 || $data->situacao == 14)
                        return $data->sessaoCongregacao.' '.
                            Yii::$app->formatter->format($data->data_homologacao_congregacao,'date');
                    return false;
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
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
