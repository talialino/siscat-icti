<?php

use kartik\grid\GridView;

?>
<div id='containerTabela'>
<h3 class="tituloRelatorio">
    <?=$titulo?>
</h3>
<?= GridView::widget([
        'dataProvider' => $relatorio,
        'id' => 'tabelaAvaliacao',
        //'pjax' => false,
        //'striped' => false,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'primary',
            'heading' => false,
            'before' => false,
            'after' => false,
            'footer' => false,
        ],
        'headerRowOptions' => ['class' => 'tabelaAvaliacaoCabecalho'],
        'summary' => '',
        'showPageSummary' => true,
        'toolbar' =>  false,
        'columns' => [
            [
                'attribute' => 'pergunta',
                'width' => '50%',
                'format' => 'html',
            ],
            [
                'attribute' => 'plenamente_satisfatorio',
                'header' => 'Plenamente<br/>Satisfatório',
                //'width' => '5%',
            ],
            'satisfatorio',
            'regular',
            [
                'attribute' => 'pouco_satisfatorio',
                'header' => 'Pouco<br/>Satisfatório',
            ],
            'insatisfatorio',
        ],
    ]);
?>
</div>