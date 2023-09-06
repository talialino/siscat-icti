<?php

use yii\helpers\Html;
use yii\helpers\URl;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisai\models\SisaiPerguntaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="sisai-pergunta-index">
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
        'dataProvider' => new ActiveDataProvider(['query' => $model->getSisaiPerguntas(), 'pagination' => false, 'sort' => false]),
        //'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  [
            ['content' => 
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['/sisai/sisaipergunta/create', 'id' => $model->id_grupo_perguntas]),
                    'title' => 'Adicionar Pergunta',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'columns' => [

            'descricao:ntext',
            'tipoPergunta',
            [
                'label' => 'Alternativas',
                'format' => 'raw',
                'value' => function($model){
                    if($model->tipo_pergunta == $model::ABERTA)
                        return '';
                            
                    return str_replace(["Array\n(\n","\n)","\n"],['','','<br />'],print_r($model->alternativas, true));
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sisaipergunta',
                'template' => '{alternativas} {update}{delete}',
                'buttons' => [
                    'alternativas' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-th-list'></span>",[
                            'value' => Url::to(['sisaipergunta/alternativas', 'id' => $model->id_pergunta]), 
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Adicionar Alternativas',
                        ]);
                        return $btn;
                    },
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sisaipergunta/update', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Atualizar Pergunta'
                        ]);
                        return $btn;
                    },
                ],
                'visibleButtons' => [
                    'alternativas' => function($model){
                        return ($model->tipo_pergunta == $model::OBJETIVA || $model->tipo_pergunta == $model::MULTIPLA_ESCOLHA);
                    },
                ],
            ],

        ],
    ]); ?>
    </div>
</div>
