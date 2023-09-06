<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeProjeto */

$this->title = 'Detalhes do Projeto';
$this->params['breadcrumbs'][] = ['label' => 'Site do IMS' , 'url' => 'http://www.ims.ufba.br'];
$this->params['breadcrumbs'][] = ['label' => 'Lista de Projetos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-projeto-view">

    <h1 class="cabecalho2"><?= Html::encode($this->title) ?></h1> 

    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => ''],
        'enableEditMode' => false,
        'attributes' => [
            'titulo:ntext',
            'tipoProjeto',
            ['attribute' => 'id_pessoa', 'value' => $model->pessoa->nome],
            ['attribute' => 'tipoExtensao', 'visible' => $model->tipo_projeto == $model::EXTENSAO],
            [
                'columns' => [
                    'data_inicio:date',
                    'data_fim:date',
                ]
            ],
            ['attribute' => 'resumo', 'visible' => $model->disponivel_site],
            ['label' => 'Equipe Executora','format' => 'raw', 'value' => GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $model->getSisapeProjetoIntegrantes()]),
                'summary' => '',
                'columns' => [
                    'nome',
                    'vinculoString',
                    'funcao',
                    'carga_horaria',
                ],
            ])],
        ],
    ]) ?>
</div>
