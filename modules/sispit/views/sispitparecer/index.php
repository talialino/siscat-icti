<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\assets\SispitAsset;

SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitParecerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lista de Pareceres';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-parecer-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'panel' => ['type' => 'primary', 'before' => false, 'after'=> false],
        'toolbar' =>  false,
        'emptyText' => 'Você não possui nenhum PIT/RIT designado para você avaliar neste ano.',
        'columns' => [
            'planoRelatorio.pessoa.nome',
            'parecer',
            'tipo',
            'pitRit',
            [
                'label' => 'Situação', 'value' => function($model, $key, $index){
                    if($model->atual == 0)
                        return 'Inativo';
                    $status = $model->planoRelatorio->status;
                    $aprovado = ($model->tipo_parecerista == $model::PARECERISTA_NUCLEO ? 3 : 7) +
                        (10 * $model->pit_rit);
                    if($status < $aprovado)
                        return 'Aguardando seu parecer';
                    if($status == $aprovado)
                        return 'Você aprovou o '.($model->pit_rit ? 'RIT' : 'PIT');
                    if($status == $aprovado+1)
                        return ($model->pit_rit ? 'RIT' : 'PIT').' necessita de correções.';
                    if($status > $aprovado+1)
                        return ($model->pit_rit ? 'RIT' : 'PIT').' aprovado '.(
                            $model->tipo_parecerista == $model::PARECERISTA_NUCLEO ? 'pelo Núcleo' : 'pela CAC'
                        );
                }
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
