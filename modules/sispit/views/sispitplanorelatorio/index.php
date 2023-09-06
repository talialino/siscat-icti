<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitPlanoRelatorioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plano Relatorios';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-plano-relatorio-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'panel' => ['type' => 'primary',],
        'toolbar' =>  ['{toggleData}',],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_plano_relatorio',
            'user_id',
            'ano',
            'data_homologacao_nucleo_pit',
            'data_homologacao_cac_pit',
            //'data_preenchimento_pit',
            //'data_homologacao_nucleo_rit',
            //'data_homologacao_cac_rit',
            //'data_preenchimento_rit',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
