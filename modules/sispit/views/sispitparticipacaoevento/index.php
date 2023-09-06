<?php

use yii\helpers\Html;
use yii\helpers\URl;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\assets\SispitAsset;
SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitParticipacaoEventoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Participação em Eventos';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-participacao-evento-index">

    <h1 class="cabecalho"><span><?= Html::encode($this->title) ?></span></h1>
    <?php
        Modal::begin([
            'header' => "<h3>$this->title</h3>",
            'id' => 'modal',
            'size' =>'modal-md',
            'options' => ['tabindex' => false,],
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'footer' => false,
        ],
        'toolbar' =>  [
            ['content' => !$plano->isEditable() ? '' :
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['sispitparticipacaoevento/create', 'id' => $searchModel->id_plano_relatorio]),
                    'title' => 'Adicionar participação em evento',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'columns' => [
            ['attribute' => 'semestre', 'group' => true, 'visible' => !Yii::$app->session->get('siscat_ano')->suplementar],
            'nome:text',
            'tipoEvento',
            'tipoParticipacao',
            'local',
            'data_inicio:date',
            'data_fim:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sispitparticipacaoevento',
                'contentOptions' => ['style' => 'width:70px;'],
                'template' => '{update} {delete}',
                'visible' => $plano->isEditable(),
                'buttons'=>[
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sispitparticipacaoevento/update', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-toggle'=>'tooltip',
                            'data-placement'=>'left',
                            'title'=>'Atualizar participação em evento'
                        ]);
                        return $btn;
                    },
                ]
            ],
        ],
    ]); ?>
    </div>
</div>
