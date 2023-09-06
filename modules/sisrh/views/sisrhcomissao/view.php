<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\grid\GridView;

use app\modules\sisrh\models\SisrhComissaoPessoa;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetor */

$this->title = 'Comissão';
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => 'Comissões', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-comissao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app','Update'), ['update', 'id' => $model->id_comissao], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','Delete'), ['delete', 'id' => $model->id_comissao], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app','Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="table-responsive">
    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => 'Informações'],
        'enableEditMode' => false,
        'attributes' => [
            ['attribute' => 'nome', 'labelColOptions' => ['style'=>'width:12%']],
            [
                'columns' => [
                    ['attribute' => 'nome','valueColOptions'=>['style'=>'width:54.66%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'eh_comissao_pit_rit','format' => 'boolean','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'sigla','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'data_inicio','format' => 'date','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'data_fim','format' => 'date','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            ['attribute' => 'observacao', 'format' => 'raw', 'labelColOptions' => ['style'=>'width:12%']],
            ['label' => Yii::t('app','Composition'),'format' => 'raw', 'labelColOptions' => ['style'=>'width:12%'], 'value' => GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => '',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'pessoa.nome',
                    'descricaoFuncao',
                ],
            ])]
            ],
    ]) ?>
</div>
</div>
