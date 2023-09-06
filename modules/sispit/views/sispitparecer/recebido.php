<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\assets\SispitAsset;

SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitParecerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pareceres recebidos';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-parecer-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'panel' => ['type' => 'primary', 'before' => false, 'after'=> false],
        'toolbar' =>  false,
        'emptyText' => 'Você ainda não recebeu nenhum parecer.',
        'columns' => [
            'nomeParecerista',
            'parecer',
            'tipo',
            'pitRit',
        ],
    ]); ?>
    </div>
</div>
