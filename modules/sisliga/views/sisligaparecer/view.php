<?php
use kartik\grid\GridView;
use kartik\detail\DetailView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'striped' =>false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Pareceres',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        'nomeParecerista',
        'parecer',
        'data:date'
    ],
]);