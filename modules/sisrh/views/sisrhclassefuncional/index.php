<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisrh\models\SisrhClasseFuncionalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Functional Classes');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-classe-funcional-index">

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
            'after' => Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => 'Adicionar Classe Funcional', 'class' => 'btn btn-success pull-right', ]).'<div style="clear: both;"></div>',
        ],
        'toolbar' =>  [
            ['content' => 
                Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => 'Adicionar Classe Funcional', 'class' => 'btn btn-success', ]),
            ],
       
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'denominacao',
            'categoria.nome',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
