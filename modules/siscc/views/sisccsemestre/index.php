<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\assets\SisccAsset;
use app\modules\siscc\models\SisccSemestre;
SisccAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\siscc\models\SisccSemestreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Semestres';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-semestre-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
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
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  [
            ['content' => 
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['create']),
                    'title' => 'Adicionar Semestre',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'ano',
            ['attribute' => 'semestre', 'value' => function($data){ return SisccSemestre::OPCOESSEMESTRE[$data->semestre];}],
            'remoto:boolean',
            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sisccsemestre',
                'template' => '{update} {delete}',  
                'visible' => true,              
                'buttons'=>[
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sisccsemestre/update', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-toggle'=>'tooltip',
                            'data-placement'=>'bottom',
                            'title'=>'Atualizar semestre'
                        ]);
                        return $btn;
                    },
                ]
            ],
        ],
    ]); ?>
    </div>
</div>
