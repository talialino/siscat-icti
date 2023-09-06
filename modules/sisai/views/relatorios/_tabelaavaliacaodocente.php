<?php

use kartik\grid\GridView;

?>
<div id='containerTabela'>
<h3 class="tituloRelatorio">
    Avaliação Docente pelos Discentes <?=$model->semestre?>: <?=$model->pessoa?><br/>
    <?=$model->componenteColegiado?><?php if($tipoQuestionario != 2) echo '*';?>
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
                'value' => function($model)
                    {
                        $pergunta = $model->getPerguntaFracionada();
                        if(is_array($pergunta))
                            return implode('<br>',$pergunta);
                        return $pergunta;
                    },
                'width' => '50%',
                'format' => 'html',
                'pageSummary'=>'Média Final',
                'pageSummaryOptions' => ['colspan' => 6],
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
            [
                'class' => 'kartik\grid\FormulaColumn', 
                'header' => 'Nota',
                'attribute' => 'media',
                'format' => ['decimal', 2],
                'pageSummary' => $relatorio->getTotalCount() > 0,
                'pageSummaryFunc' => GridView::F_AVG,
            ],
        ],
    ]);
?>
</div>
<?php if($tipoQuestionario == 16) echo '<div id="copiaTabelaOnline"></div>';?>