<?php
use kartik\grid\GridView;
use kartik\detail\DetailView;

$programa = $searchModel->programaComponenteCurricular;

echo DetailView::widget([
    'model' => $programa,
    'panel' => ['type' => 'default', 'heading' => 'Programa de Componente Curricular', 'before' => false,
    'after' => false],
    'enableEditMode' => false,
    'attributes' => [
        'semestreString',            
        'componente',
        'colegiado',
    ]
]);

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
        'tipo',
        'parecer',
        'data:date'
    ],
]);