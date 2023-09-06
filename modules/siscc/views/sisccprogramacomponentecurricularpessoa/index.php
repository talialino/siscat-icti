<?php

use yii\helpers\Html;
use yii\helpers\URl;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Modal;
use app\assets\SisccAsset;
SisccAsset::register($this);

$this->registerJsFile(
    '@web/js/sisccSituacaoCores.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

/* @var $this yii\web\View .*/
/* @var $searchModel app\modules\siscc\models\SisccProgramaComponenteCurricularPessoaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Meus Programas';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-programa-componente-curricular-pessoa-index">

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
        'toolbar' =>  [ ],
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' =>'semestre.string','width' => '7%'],
            ['attribute' =>'programaComponenteCurricular.colegiado','width' => '13%'],
            ['attribute' =>'programaComponenteCurricular.componenteCurricular.codigoNome','width' => '15%'],
            ['attribute' =>'programaComponenteCurricular.situacaoString','width' => '15%','contentOptions' => ['class' => 'situacaoCores'],],
            ['label' => 'Pareceres','width' => '40%','value' => function($data){return GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $data->programaComponenteCurricular->getSisccPareceres(), 'sort' => false]),
                'summary' => '',
                'responsiveWrap' => false,
                'columns' => [
                    ['attribute' =>'parecer','width' => '100%'],
                ],
            ]);}, 'format' => 'raw'],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:7%'],
                'template' => '{update}{import}{view} {pdf}',
                'controller' => 'sisccprogramacomponentecurricular',
                'buttons' => [
                    'update' => function($url,$model,$key){
                        $btn = Html::a("<span class='glyphicon glyphicon-pencil'></span>",
                            Url::to(['sisccprogramacomponentecurricular/editar', 'id' => $model->programaComponenteCurricular->id_programa_componente_curricular]), [
                            'data-placement'=>'bottom',
                            'title'=>'Editar Programa de Componente Curricular',
                            ]);
                        return $btn;
                    },
                    'import' => function($url,$model,$key){
                        $btn = Html::button("<i class='glyphicon glyphicon-import'></i>",
                        [
                            'value' =>Url::to(['sisccprogramacomponentecurricular/importarprograma', 'id' => $model->programaComponenteCurricular->id_programa_componente_curricular]),
                            'title' => 'Importar Programa Anterior',
                            'data-placement'=>'bottom',
                            'class' => 'editModalButton modalButton'
                        ]);
                        return $btn;
                    },
                    'view' => function($url,$model,$key){
                        $btn = Html::a("<span class='glyphicon glyphicon-eye-open'></span>",
                            Url::to(['sisccprogramacomponentecurricular/view', 'id' => $model->programaComponenteCurricular->id_programa_componente_curricular]), [
                            'data-placement'=>'bottom',
                            'title'=>'Visualizar Programa de Componente Curricular'
                        ]);
                        return $btn;
                    },
                    'pdf' => function($url,$model,$key){
                        $btn = Html::a(Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']),
                        Url::to(['/siscc/sisccprogramacomponentecurricular/pdf', 'id' => $model->id_programa_componente_curricular]), [
                            'data-placement'=>'bottom',
                            'title'=>'Baixar PDF',
                        ]);
                        return $btn;
                    },
                ],
                'visibleButtons' => [
                    'update' => function($model){
                        return $model->programaComponenteCurricular->isEditable();
                    },
                    'import' => function($model){
                        return $model->programaComponenteCurricular->podeImportar();
                    },
                    'pdf' => function($model){
                        return $model->programaComponenteCurricular->situacao > 0;
                    }, 
                ],
            ],
        ],
    ]); ?>
    </div>
</div>
