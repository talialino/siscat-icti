<?php
use kartik\grid\GridView;
use app\components\SiscatPdfWidget;

$documento = $searchModel->documento;

$nome = "Pareceres - $searchModel->documentoTitulo.pdf";

SiscatPdfWidget::begin([
    'nome' => $nome,
    'filename' => $nome,
    'modulo' => 'SISLIGA - Sistema de Ligas Acadêmicas',
    'title' => $nome,
    'cssInLine' => '.summary, .btn-print{ display:none }'
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
            'documentoTitulo',
            'nomeParecerista',
            'parecer',
            'data:date'
        ],
    ]);

SiscatPdfWidget::end();