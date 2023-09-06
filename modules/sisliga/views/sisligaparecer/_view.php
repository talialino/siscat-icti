<?php
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;


echo GridView::widget([
    'dataProvider' => new ActiveDataProvider(['query' => $model->getSisligaPareceres()->where(['<>', 'id_parecer',$parecer->id_parecer]),]),
    'striped' =>false,
    'hover' => true,
    'rowOptions' => ['class' => 'wordBreak'],
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Pareceres Anteriores',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        'parecer',
        'data:date'
    ],
]);