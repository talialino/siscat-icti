<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\assets\SisligaAsset;

SisligaAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisliga\models\SisligaParecerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lista de Pareceres';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisliga'), 'url' => ['/'.strtolower('sisliga')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisliga-parecer-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'panel' => ['type' => 'primary', 'before' => false, 'after'=> false],
        'toolbar' =>  false,
        'emptyText' => 'Você não possui nenhuma liga ou relatório de liga designado para você apreciar.',
        'columns' => [
            'documentoTitulo',
            ['attribute' => 'parecer', 'width' => '30%'],
            [
                'label' => 'Situação', 'value' => function($model, $key, $index){
                    if($model->atual == 0)
                        return 'Inativo';
                    $situacao = $model->documento->situacao;
                    $aprovado =  3;
                    if($situacao < $aprovado)
                        return 'Aguardando seu parecer';
                    if($situacao == $aprovado)
                        return 'Você aprovou a liga';
                    if($situacao == $aprovado+1)
                        return 'Liga necessita de correções';
                    if($situacao == $aprovado+2)
                        return 'Liga modificado aguardando novo parecer';
                    if($situacao > $aprovado+2)
                        return 'Liga aprovada pela comissão';
                }, 'width' => '15%'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'visibleButtons' => [
                    'update' => function($model, $key, $index){
                        return $model->isEditable();
                }]
            ],
        ],
    ]); ?>
    </div>
</div>
