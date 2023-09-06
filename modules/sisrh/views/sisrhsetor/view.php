<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\grid\GridView;
use app\modules\sisrh\models\SisrhSetorPessoa;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetor */

$this->title = Yii::t('app','Sector');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Sectors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-setor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app','Update'), ['update', 'id' => $model->id_setor], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','Delete'), ['delete', 'id' => $model->id_setor], [
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
            [
                'columns' => [
                    ['attribute' => 'nome','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'sigla','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'id_setor_responsavel','value' => $model->setorResponsavel ? $model->setorResponsavel->nome : '-','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'codigo','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'email','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'ramais','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'eh_colegiado', 'format' => 'boolean','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'eh_nucleo_academico', 'format' => 'boolean','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            ['attribute' => 'observacao', 'format' => 'raw',  'labelColOptions' => ['style'=>'width:12%']],
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
