<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\assets\SisccAsset;

SisccAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\siscc\models\SisccParecerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lista de Pareceres';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-parecer-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'panel' => ['type' => 'primary', 'before' => false, 'after'=> false],
        'toolbar' =>  false,
        'emptyText' => 'Você não possui nenhum projeto ou relatório de projeto designado para você apreciar.',
        'columns' => [
            'documentoTitulo',
            ['attribute' => 'parecer', 'width' => '30%'],
            ['attribute' => 'tipo', 'label' => 'Tipo'],
            [
                'label' => 'Situação', 'value' => function($model, $key, $index){
                    if($model->atual == 0)
                        return 'Inativo';
                    $situacao = $model->documento->situacao;
                    $aprovado = ($model->tipo_parecerista == $model::PARECERISTA_NUCLEO ? 3 : 8);
                    if($situacao < $aprovado)
                        return 'Aguardando seu parecer';
                    if($situacao == $aprovado)
                        return 'Você aprovou o programa';
                    if($situacao == $aprovado+1)
                        return 'Programa necessita de correções';
                    if($situacao == $aprovado+2)
                        return 'Programa modificado aguardando novo parecer';
                    if($situacao > $aprovado+2)
                        return 'Programa aprovado '.(
                            $model->tipo_parecerista == $model::PARECERISTA_NUCLEO ? 'pelo núcleo' : 'pela COPEX'
                        );
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
