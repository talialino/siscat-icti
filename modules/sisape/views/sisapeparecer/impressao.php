<?php
use kartik\grid\GridView;
use kartik\detail\DetailView;
use app\components\SiscatPdfWidget;

$documento = $searchModel->documento;

$nome = "Pareceres - $searchModel->documentoTitulo.pdf";

SiscatPdfWidget::begin([
    'nome' => $nome,
    'filename' => $nome,
    'modulo' => 'SISAPE - Sistema Acompanhamento de Projeto de Pesquisa e Extensão',
    'title' => $nome,
    'cssInLine' => '.summary, .btn-print{ display:none }'
]);

    /*echo DetailView::widget([
        'model' => $documento,
        'panel' => ['type' => 'default', 'heading' => , 'before' => false,
        'after' => false],
        'enableEditMode' => false,
        'attributes' => [
            'semestreString',            
            'componente',
            'colegiado',
        ]
    ]);*/

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'striped' =>false,
        'hover' => true,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'default',
            'heading' => 'Pareceres - '.($searchModel->tipo_parecerista == $searchModel::PARECERISTA_COPEX ? 'COPEX' : 'Núcleo'),
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