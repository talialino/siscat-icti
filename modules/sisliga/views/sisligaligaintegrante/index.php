<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisape\models\SisapeProjetoIntegranteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sisape Projeto Integrantes');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-projeto-integrante-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'after' => Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => Yii::t('app', 'Adicionar Sisape Projeto Integrante'), 'class' => 'btn btn-success pull-right', ]).'<div style="clear: both;"></div>',
        ],
        'toolbar' =>  [
            ['content' => 
                Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => Yii::t('app', 'Adicionar Sisape Projeto Integrante'), 'class' => 'btn btn-success', ]),
            ],
            '{toggleData}',
        ],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_projeto_integrante',
            'id_projeto',
            'id_integrante_externo',
            'id_pessoa',
            'funcao',
            //'id_aluno',
            //'carga_horaria',
            //'vinculo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
