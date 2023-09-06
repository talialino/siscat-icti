<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\assets\SisaiAsset;

SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisai\models\SisaiColegiadoSemestreAtuvSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Colegiado Semestre Atuvs');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-colegiado-semestre-atuv-index">

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
            ['content' => 
                Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => Yii::t('app', 'Adicionar Sisai Colegiado Semestre Atuv'), 'class' => 'btn btn-success', ]),
            ],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'colegiadosLiberados',
            'semestre.string',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
