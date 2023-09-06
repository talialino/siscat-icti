<?php

use yii\helpers\Html;
use yii\helpers\URl;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\assets\SispitAsset;
SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitAfastamentoDocenteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ocorrências';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-afastamento-docente-index">

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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsiveWrap' => false,
        //'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'footer' => false,
        ],
        'toolbar' =>  [
            ['content' => !$plano->isEditable() ? '' :
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['sispitafastamentodocente/create', 'id' => $searchModel->id_plano_relatorio]),
                    'title' => 'Adicionar Ocorrência',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'columns' => [
            ['attribute' => 'semestre', 'group' => true, 'visible' => !Yii::$app->session->get('siscat_ano')->suplementar],
            ['attribute' =>'descricao','width' => '50%'],
            'eh_afastamento:boolean',
            'nivelGraduacao',
            'carga_horaria',
            'data_inicio:date',
            'data_fim:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sispitafastamentodocente',
                'contentOptions' => ['style' => 'width:70px;'],
                'template' => '{update} {delete}',
                'visible' => $plano->isEditable(),
                'buttons'=>[
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sispitafastamentodocente/update', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-toggle'=>'tooltip',
                            'data-placement'=>'left',
                            'title'=>'Atualizar ocorrência'
                        ]);
                        return $btn;
                    },
                ]
            ],
        ],
    ]); ?>
</div>
