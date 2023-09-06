<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use yii\grid\GridView;

use app\modules\sisrh\models\SisrhSetorPessoa;
use app\modules\sisrh\models\SisrhSetorPessoaSearch;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetor */

$searchModel = new SisrhSetorPessoaSearch();
$dataProvider = $searchModel->searchArray($model->id_setor);

?>
<div class="sisrh-setor-view">
    <div class="table-responsive">
    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'default', 'heading' => 'Outras Informações'],
        'enableEditMode' => false,
        'attributes' => [
            [
                'columns' => [
                    ['attribute' => 'email','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'ramais','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            ['label' => Yii::t('app','Composition'),'format' => 'raw', 'labelColOptions' => ['style'=>'width:12%'], 'value' => GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => '',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'pessoa.nome',
                        'value' => function($model){ 
                            return $model->pessoa->temOcorrenciaVigente() ? "<span class='ocorrenciaVigente'>{$model->pessoa->nome}</span>" : $model->pessoa->nome;
                        },
                        'format' => 'raw',
                    ], 
                    'descricaoFuncao',
                    ['class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'visible' => Yii::$app->user->can('sisrhsetor'),
                            'buttons' => [
                                'view' => function($url,$model,$key){
                                    $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-eye-open"]);
                                    $options = [
                                        'title' => 'Visualizar pessoa',
                                        'aria-label' => 'Visualizar pessoa',
                                        'data-pjax' => '0',
                                    ];
                                    return Html::a($icon, Url::toRoute(['/sisrh/sisrhpessoa/view', 'id' => $model->id_pessoa]), $options);
                                }
                            ]
                        ],
                ],
            ])],
            ['attribute' => 'observacao', 'format' => 'raw',  'labelColOptions' => ['style'=>'width:12%']],
            
        ],
    ]) ?>
</div>
</div>
