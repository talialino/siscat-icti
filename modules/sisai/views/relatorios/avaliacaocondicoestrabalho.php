<?php

use yii\helpers\Html;

use app\assets\SisaiAsset;

SisaiAsset::register($this);

$this->registerJsFile('@web/js/chart.min.js');
$this->registerJsFile('@web/js/jspdf.umd.min.js');
$this->registerJsFile('@web/js/html2canvas.min.js');

$this->title = 'Relatórios de Avaliação Discente';

?>

<div class="sisai-relatorios-discente row">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_search', ['model' => $model])?>

        <?php if($relatorio):?>

            <div class="bodyRelatorio">

                <?= $this->render('_tabelaavaliacaodiscente', ['model' => $model, 'relatorio' => $relatorio])?>
                <?php if($relatorio->getTotalCount() > 0):?>
                    <div class="graficoRelatorio">
                        <?= $this->render('_graficoavaliacaodiscente', ['model' => $model, 'relatorio' => $relatorio])?>
                    </div>
                    <p id='dataRelatorio' style="text-align: right; margin-right: 10px;">
                        Relatório gerado em: <?=date('d/m/Y')?>.
                    </p>
                <?php endif;?>
            </div>
        <?php endif;?>
</div>