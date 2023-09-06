<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View .*/
/* @var $searchModel app\modules\sisrh\models\SisrhAfastamentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Occurrence');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisrh')), 'url' => ['/'.strtolower(Yii::t('app', 'sisrh'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-contatos">

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
                    'title' => 'Adicionar Ocorrência',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
            '{toggleData}',
        ],
        'columns' => [

            'pessoa.nome',
            'ocorrencia.justificativa',
            'inicio:date',
            'termino:date',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sisrhafastamento',
                'contentOptions' => ['style' => 'width:100px;'],
                'template' => '{view} {update} {delete}',  
                'visible' => true,              
                'buttons'=>[
                    'view' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-eye-open'></span>",[
                            'value'=>Url::to(['sisrhafastamento/view', 'id' => $key]), 
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Visualizar Ocorrência'
                        ]);
                        return $btn;
                    },
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sisrhafastamento/update', 'id' => $key]), 
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Atualizar Ocorrência'
                        ]);
                        return $btn;
                    },
                ]
            ],
        ],
    ]); ?>
    </div>
</div>
