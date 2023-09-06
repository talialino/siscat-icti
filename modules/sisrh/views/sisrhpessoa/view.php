<?php

use yii\helpers\Html;
use kartik\detail\DetailView; 
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhPessoa */

$this->title = Yii::t('app','Person');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','People'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$model->emails = implode(' | ',$model->emails);
$model->telefone = implode(' | ',$model->telefone);
?>
<div class="sisrh-pessoa-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app','Update'), ['update', 'id' => $model->id_pessoa], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','Delete'), ['delete', 'id' => $model->id_pessoa], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app','Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => 'Informações'],
        'enableEditMode' => false,
        'attributes' => [
            [
                'columns' => [
                    ['attribute' => 'nome','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'id_user', 'value' => $model->user ? $model->user->username : '','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'siape','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                ],
            ],
            [
                'columns' => [
                    ['attribute' => 'dt_nascimento', 'format' => 'date','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'sexo','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'estadoCivil','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'telefone','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'emails','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'grauEscolaridade','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'id_cargo', 'value' => $model->cargo ? $model->cargo->descricao : '','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'id_classe_funcional', 'value' => $model->classeFuncional ? $model->classeFuncional->denominacao : '','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'jornada','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'cpf','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'dt_ingresso_orgao', 'format' => 'date','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'situacao','format' => 'raw','value' => $model->situacao ? '<span class="label label-success">Ativo</span>' : '<span class="label label-danger">Inativo</span>', 'labelColOptions' => ['style'=>'width:12%'],'valueColOptions'=>['style'=>'width:21.33%']],
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'dt_vigencia', 'format' => 'date','valueColOptions'=>['style'=>'width:21.33%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'nomePessoaVinculada', 'labelColOptions' => ['style'=>'width:12%'],'valueColOptions'=>['style'=>'width:54.66%']],
                ], 'visible' => $model->id_cargo == 2
            ],
            ['label' => 'Setores','format' => 'raw', 'labelColOptions' => ['style'=>'width:12%'], 'value' => GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $model->getSisrhSetorPessoas()]),
                'summary' => '',
                'columns' => [
                    'setor.nome',
                    'descricaoFuncao',
                ],
            ])],
            ['label' => 'Comissões','format' => 'raw', 'labelColOptions' => ['style'=>'width:12%'], 'value' => GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $model->getSisrhComissaoPessoas()]),
                'summary' => '',
                'columns' => [
                    'comissao.nome',
                    'descricaoFuncao',
                ],
            ])],
            ['label' => 'Ocorrências','format' => 'raw', 'labelColOptions' => ['style'=>'width:12%'], 'value' => GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $model->getAfastamentos(),
                    'sort' => [
                        'defaultOrder' => [
                            'id_afastamento' => SORT_DESC,
                        ]
                    ]]),
                'summary' => '',
                'columns' => [
                    ['attribute' => 'ocorrencia.justificativa', 'label' => 'Justificativa'],
                    'inicio:date',
                    'termino:date',
                ],
            ])]
        ],
    ]) ?>

</div>
