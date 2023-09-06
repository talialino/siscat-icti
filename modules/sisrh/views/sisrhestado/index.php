<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisrh\models\SisrhEstadoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Estados';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisrh'), 'url' => ['/'.strtolower('sisrh')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-estado-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'primary',
            'after' => Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => 'Adicionar Estado', 'class' => 'btn btn-success pull-right', ]).'<div style="clear: both;"></div>',
        ],
        'toolbar' =>  [
            ['content' => 
                Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => 'Adicionar Estado', 'class' => 'btn btn-success', ]),
            ],
            '{toggleData}',
        ],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sigla',
            'nome',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
