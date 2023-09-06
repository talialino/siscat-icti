<?php
use kartik\grid\GridView;
use kartik\detail\DetailView;
use app\components\SiscatPdfWidget;

$programa = $searchModel->programaComponenteCurricular;

$nome = "Pareceres - $programa->componente.pdf";

SiscatPdfWidget::begin([
    'nome' => $nome,
    'filename' => $nome,
    'modulo' => 'SISCC - Sistema de Componentes Curriculares',
    'title' => $nome,
    'cssInLine' => '.summary, .btn-print{ display:none }'
]);

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
            'heading' => 'Pareceres - '.($searchModel->tipo_parecerista == $searchModel::PARECERISTA_CAC ? 'CAC' : 'Colegiado'),
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

SiscatPdfWidget::end();