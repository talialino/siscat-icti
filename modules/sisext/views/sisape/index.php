<?php

use yii\helpers\Html;
use yii\helpers\URl;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisape\models\SisapeProjetoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerJs(
    "function pesquisaAvancadaToggle(){
        $('.sisape-projeto-search').toggle();
        return false;
    }",
    yii\web\View::POS_HEAD,
    'pesquisa-avancada-toggle'
);

$this->title = 'Lista de Projetos - UFBA/Vitória da Conquista';
$this->params['breadcrumbs'][] = ['label' => 'Site do IMS' , 'url' => 'http://www.ims.ufba.br'];
$this->params['breadcrumbs'][] = 'Lista de Projetos';
?>
<div class="sisape-projeto-index">

    <h2 class="cabecalho2"><?= Html::encode($this->title) ?></h2>
    <p class="pesquisa-avancada pull-right"><a href="#" onclick="pesquisaAvancadaToggle()">Pesquisa Avançada</a></p>
    <div class="clearfix"></div>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsiveWrap'=>false,
        'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  false,

        'columns' => [
            ['attribute' => 'titulo', 'format' => 'raw', 'value' => function($data){
                return Html::a($data->titulo,
                    Url::to(['/sisext/sisape/view', 'id' => $data->id_projeto]),
                    ['title' => 'Visualizar','data-toggle'=>'tooltip']);
            }],
            'pessoa.nome',
            'tipoProjeto',
            'data_inicio:date',
            'data_fim:date',
        ],
    ]); ?>
    </div>
</div>
