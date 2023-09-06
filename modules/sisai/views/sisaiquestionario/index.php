<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisai\models\SisaiQuestionarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Questionários';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisai'), 'url' => ['/'.strtolower('sisai')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-questionario-index">

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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => false,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  [
            ['content' => 
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['create']),
                    'title' => 'Adicionar Questionário',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'filterModel' => $searchModel,
        'columns' => [
            'titulo',
            'tipoQuestionario',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sisaiquestionario',
                'contentOptions' => ['style' => 'width:100px;'],
                'template' => '{view} {update} {delete}',  
                'visible' => true,              
                /* 'buttons'=>[
                    'view' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-eye-open'></span>",[
                            'value'=>Url::to(['sisaiquestionario/view', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            //'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Visualizar Questionário'
                        ]);
                        return $btn;
                    },
                ] */
            ],
        ],
    ]); ?>
    </div>
</div>
