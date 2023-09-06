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
    $perguntas[] = $item->perguntaResumida;
    $plenamente_satisfatorio[] = round($item->plenamente_satisfatorio * 100 / $item->total, 2);
    $satisfatorio[] = round($item->satisfatorio * 100 / $item->total, 2);
    $regular[] = round($item->regular * 100 / $item->total, 2);
    $pouco_satisfatorio[] = round($item->pouco_satisfatorio * 100 / $item->total, 2);
    $insatisfatorio[] = round($item->insatisfatorio * 100 / $item->total, 2);
}
?>
<div id="containerGrafico2">
    <?php for($i = 0; $i < count($perguntas); $i++):?>
        <div class="col-sm-6">
        <?= Chart::widget([
            'id' => "grafico$i",
            
            'type' => Chart::TYPE_PIE,
            'labels' => [],
            'datasets' => [
                [
                    'data' => [$plenamente_satisfatorio[$i],$satisfatorio[$i],$regular[$i],$pouco_satisfatorio[$i],$insatisfatorio[$i],],
                    'label' => 'Dados',
                    'backgroundColors' => ['rgba(75, 192, 192, 0.8)','rgba(54, 162, 235, 0.8)','rgba(255, 205, 86, 0.8)','rgba(255, 159, 64, 0.8)','rgba(255, 99, 132, 0.8)',],
                    'borderColors' => ['rgba(75, 192, 192, 1)','rgba(54, 162, 235, 1)','rgba(255, 205, 86, 1)','rgba(255, 159, 64, 1)','rgba(255, 99, 132, 1)'],
                ],
            ],
            'clientOptions' => [
                'plugins' => [
                    'legend' => ['position' => 'bottom',],
                    'title' => [
                        'display' => true,
                        'text' => $perguntas[$i],
                        
                    ]
                ],
            ],
                    
        ]);?>
        </div>
    <?php endfor;?>
</div>