<?php
use kartik\grid\GridView;
use kartik\detail\DetailView;
use app\components\SiscatPdfWidget;

$plano = $searchModel->planoRelatorio;
if(!$pit_rit && $plano->status > 9)//caso essa seja uma lista de pareceres do PIT, ignora a situação do RIT para a tela atual
    $plano->status = 9;

$nome = 'Pareceres '.($pit_rit ? "RIT" : "PIT")." - {$plano->pessoa->nome}.pdf";

SiscatPdfWidget::begin([
    'nome' => $nome,
    'filename' => $nome,
    'modulo' => 'SISPIT - Sistema de PIT/RIT',
    'title' => $nome,
    'cssInLine' => '.summary, .btn-print{ display:none }'
]);

    echo DetailView::widget([
        'model' => $plano,
        'panel' => ['type' => 'default', 'heading' => $pit_rit ? 'Relatório Individual de Trabalho' : 'Plano Individual de Trabalho', 'before' => false,
        'after' => false],
        'enableEditMode' => false,
        'attributes' => [
            ['label' => 'Nome', 'value' => $plano->pessoa->nome],
            [
                'columns' => [
                    ['attribute' => 'ano'],
                    ['attribute' => 'situacao'],
                ]
            ]
        ]
    ]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'striped' =>false,
        'hover' => true,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'default',
            'heading' => 'Pareceres - '.($searchModel->tipo_parecerista == $searchModel::PARECERISTA_CAC ? 'CAC' : 'Núcleo'),
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