<?php

use practically\chartjs\Chart;

$perguntas = array();
$plenamente_satisfatorio = array();
$satisfatorio = array();
$regular = array();
$pouco_satisfatorio = array();
$insatisfatorio = array();
foreach($relatorio->getModels() as $item)
{
    $perguntas[] = $tipoQuestionario == 16 ? $item->perguntaResumida : $item->perguntaFracionada;
    $plenamente_satisfatorio[] = round($item->plenamente_satisfatorio * 100 / $item->total, 2);
    $satisfatorio[] = round($item->satisfatorio * 100 / $item->total, 2);
    $regular[] = round($item->regular * 100 / $item->total, 2);
    $pouco_satisfatorio[] = round($item->pouco_satisfatorio * 100 / $item->total, 2);
    $insatisfatorio[] = round($item->insatisfatorio * 100 / $item->total, 2);
}
?>
<div id="containerGrafico">

    <?= Chart::widget([
        'id' => 'grafico',
        
        'type' => Chart::TYPE_BAR,
        'labels' => $perguntas,
        'datasets' => [
            [
                'data' => $plenamente_satisfatorio,
                'label' => 'Plenamente Satisfatório',
                'backgroundColors' => 'rgba(75, 192, 192, 0.8)',
                'borderColors' => 'rgba(75, 192, 192, 1)',
            ],
            [
                'data' => $satisfatorio,
                'label' => 'Satisfatório',
                'backgroundColors' => 'rgba(54, 162, 235, 0.8)',
                'borderColors' => 'rgba(54, 162, 235, 1)',
            ],
            [
                'data' => $regular,
                'label' => 'Regular',
                'backgroundColors' => 'rgba(255, 205, 86, 0.8)',
                'borderColors' => 'rgba(255, 205, 86, 1)',
            ],
            [
                'data' => $pouco_satisfatorio,
                'label' => 'Pouco Satisfatório',
                'backgroundColors' => 'rgba(255, 159, 64, 0.8)',
                'borderColors' => 'rgba(255, 159, 64, 1)',
            ],
            [
                'data' => $insatisfatorio,
                'label' => 'Insatisfatório',
                'backgroundColors' => 'rgba(255, 99, 132, 0.8)',
                'borderColors' => 'rgba(255, 99, 132, 1)',
            ],
        ],
        'clientOptions' => [
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'stacked' => 'true',
                    'max' => 100,
                    'title' => ['display' => true,'text' => '(%)'],
                ],
                'y' => [
                    'stacked' => 'true',
                ]
                ],
            'plugins' => [
                'legend' => ['position' => 'bottom',],
                'title' => [
                    'display' => true,
                    'text' => "Avaliação Docente pelos Discentes $model->semestre: $model->pessoa - $model->componenteColegiado",
                    
                ]
            ],
        ],
                
    ]);
?>
</div>