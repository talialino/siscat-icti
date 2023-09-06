<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\URl;
use yii\bootstrap\Modal;
use app\assets\SisccAsset;
use yii\data\ActiveDataProvider;
SisccAsset::register($this);


/* @var $this yii\web\View */
/* @var $searchModel app\modules\siscc\models\SisccProgramaComponenteCurricularSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programas de Componentes Curriculares';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-programa-componente-curricular-index">

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

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

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
            [
                'content' => $searchModel->semestre->podeImportar() ? Html::button('<i class="glyphicon glyphicon-import"></i>',
                    [
                    'value' =>Url::to(['importarsemestre', 'id' => $searchModel->semestre->id_semestre]),
                    'title' => "Importar para o semestre {$searchModel->semestre->string}",
                    'class' => 'btn btn-primary modalButton',
                    ]) : null,
            ],
            ['content' => 
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['create']),
                    'title' => 'Adicionar Programa de Componente Curricular',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
            '{toggleData}',
        ],
        'columns' => [
            ['attribute' =>'componente','width' => '25%'],
            ['attribute' =>'colegiado','width' => '20%'],
            ['label' => 'Docentes', 'width'=> '25%', 'value' => function($data){return GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $data->getSisccProgramaComponenteCurricularPessoas()]),
                'showPageSummary' => false,
                'responsiveWrap' => false,
                'showHeader'=> false,
                'columns' => [
                    'pessoa.nome',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'controller' => 'sisccprogramacomponentecurricularpessoa',
                    ]
                ],
            ]);}, 'format' => 'raw'],
            ['attribute' =>'situacaoString','width' => '20%'],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:100px;'],
                'template' => '{docente}{update}{delete}',
                'buttons' => [
                    'docente' => function($url,$model,$key){
                        $btn = Html::button("+<span class='glyphicon glyphicon-user'></span>",[
                            'value'=>Url::to(['/siscc/sisccprogramacomponentecurricularpessoa/create', 'id' => $key]), 
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Adicionar Docentes'
                        ]);
                        return $btn;
                    },
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sisccprogramacomponentecurricular/update', 'id' => $key]), 
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Atualizar Programa de Componente Curricular'
                        ]);
                        return $btn;
                    },
                    'delete' => function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $key], [
                            'class' => '',
                            'data' => [
                                'confirm' => "Deseja realmente excluir este item? Todas as tramitações serão perdidas.\nEssa ação não pode ser desfeita.",
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
                'visibleButtons' => [
                    'delete' => function ($model) use ($searchModel){
                        if($model->id_semestre != $searchModel->id_semestre)
                            return false;
                        return true;
                    },
                    'update' => function($model) use ($searchModel){
                        if($model->situacao >= 1 || $model->id_semestre != $searchModel->id_semestre)
                            return false;
                        return true;
                    }
                ],
            ],
        ],
    ]); ?>
    </div>
</div>
