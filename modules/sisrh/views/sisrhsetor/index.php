<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisrh\models\SisrhSetorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Sectors');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-setor-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'pjax' => true,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  [
            ['content' => Yii::$app->user->can('siscatAdministrar') ?
                Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => 'Adicionar Setor', 'class' => 'btn btn-success', ]) : '',
            ],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'width' => '50px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail' => function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('_view', ['model' => $model]);
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'expandOneOnly' => true
            ],
            ['attribute' => 'nome', 'width' => '40%'],
            'sigla',
            'setorResponsavel.nome',
            ['class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'width:5%;'],
            'template' => Yii::$app->user->can('siscatAdministrar') ? '{update}{delete}' :
                    (Yii::$app->user->can('sisrhsetor') ? '{update}' : '')] ,
        ],
    ]); ?>
    </div>
</div>
