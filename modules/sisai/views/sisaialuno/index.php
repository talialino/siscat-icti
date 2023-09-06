<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\assets\SisaiAsset;
use app\modules\sisrh\models\SisrhSetor;
use yii\helpers\ArrayHelper;

SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisai\models\SisaiAlunoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Alunos';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisai'), 'url' => ['/'.strtolower('sisai')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-aluno-index">

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
                    'title' => 'Adicionar Aluno',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nome',
            'matricula',
            'user.username',
            'email:email',
            [
                'attribute' => 'id_setor',
                'value' => function($model){return $model->colegiadoEscolaridade;},
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(SisrhSetor::find()->where(['eh_colegiado' => 1])->all(), 'id_setor', 'colegiado'), 
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Colegiado'],
                'width' => '17%',
            ],
            [
                'attribute' => 'ativo', 
                'class' => '\kartik\grid\BooleanColumn',
                'trueLabel' => 'Ativo', 
                'falseLabel' => 'Inativo'
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sisaialuno',
                'contentOptions' => ['style' => 'width:100px;'],
                'template' => '{view} {update} {delete}',  
                'visible' => true,              
                'buttons'=>[
                    'view' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-eye-open'></span>",[
                            'value'=>Url::to(['sisaialuno/view', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Visualizar aluno'
                        ]);
                        return $btn;
                    },
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sisaialuno/update', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Atualizar Aluno'
                        ]);
                        return $btn;
                    },
                ]
            ],
        ],
    ]); ?>
    </div>
</div>
