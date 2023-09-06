<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\assets\SisccAsset;
SisccAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\siscc\models\SisccComponenteCurricularSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Componentes Curriculares';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-componente-curricular-index">

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
        'hover' => true,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  [
            ['content' => 
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['create']),
                    'title' => 'Adicionar Componente Curricular',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
            '{toggleData}',
        ],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nome',
            'codigo_componente',
            'ch_teorica',
            'ch_pratica',
            'ch_estagio',
            // 'modulo_teoria',
            // 'modulo_pratica',
            // 'modulo_estagio',
            //'ementa:ntext',
            //'ativo',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'siscccomponentecurricular',
                'contentOptions' => ['style' => 'width:100px;'],
                'template' => '{view} {update} {delete}',  
                'visible' => true,              
                'buttons'=>[
                    'view' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-eye-open'></span>",[
                            'value'=>Url::to(['siscccomponentecurricular/view', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Visualizar Componente Curricular'
                        ]);
                        return $btn;
                    },
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['siscccomponentecurricular/update', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Atualizar Componente Curricular'
                        ]);
                        return $btn;
                    },
                ]
            ],
        ],
    ]); ?>
    </div>
</div>
