<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\grid\GridView;

use app\modules\sisrh\models\SisrhComissaoPessoa;
use app\modules\sisrh\models\SisrhComissaoPessoaSearch;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhComissao */

$searchModel = new SisrhComissaoPessoaSearch();
$dataProvider = $searchModel->search($model->id_comissao);

?>
<div class="sisrh-comissao-view">
    <div class="table-responsive">
    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'default', 'heading' => 'Outras Informações'],
        'enableEditMode' => false,
        'attributes' => [
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
                ],
            ])],
            ['attribute' => 'observacao', 'format' => 'raw', 'labelColOptions' => ['style'=>'width:12%']],
            ],
    ]) ?>
</div>
</div>
