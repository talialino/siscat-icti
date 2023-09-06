<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisai\models\SisaiPeriodoAvaliacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Períodos de Avaliação';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisai'), 'url' => ['/'.strtolower('sisai')]];
$this->params['breadcrumbs'][] = $this->title;

//Esse comando serve para corrigir a data exibida, já que o sistema entende que a data armazenada no banco está em UTC.
//Quando exibia, ele fazia automaticamente a conversão para America/Bahia, o que reduzia em 3h o horário.
Yii::$app->setTimeZone('UTC');

?>
<div class="sisai-periodo-avaliacao-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'panel' => [
            'type' => 'primary',
        ],
        'toolbar' =>  [
            ['content' => Yii::$app->session->get('siscat_periodo_avaliacao',false) ? '' :
                Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => 'Adicionar Período Avaliação', 'class' => 'btn btn-success', ]),
            ],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            'semestre.string',
            'data_inicio:datetime',
            'data_fim:datetime',
            //'questionarios',

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => function($model,$key,$index){
                        $periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao',false);
                        if($periodoAvaliacao && $model->id_semestre != $periodoAvaliacao->id_semestre)
                            return false;
                        return true;
                    },
                    'delete' => function($model,$key,$index){
                        $periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao',false);
                        if($periodoAvaliacao && $model->id_semestre != $periodoAvaliacao->id_semestre)
                            return false;
                        return true;
                    },
                ]
            ],
        ],
    ]); ?>
    </div>
</div>
