<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisape\models\SisapeIntegranteExternoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sisape Integrante Externos');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-integrante-externo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'after' => Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => Yii::t('app', 'Adicionar Sisape Integrante Externo'), 'class' => 'btn btn-success pull-right', ]).'<div style="clear: both;"></div>',
        ],
        'toolbar' =>  [
            ['content' => 
                Html::a('<i class="glyphicon glyphicon-plus"></i>',['create'], ['title' => Yii::t('app', 'Adicionar Sisape Integrante Externo'), 'class' => 'btn btn-success', ]),
            ],
            '{toggleData}',
        ],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_integrante_externo',
            'nome',
            'email:email',
            'telefone',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
