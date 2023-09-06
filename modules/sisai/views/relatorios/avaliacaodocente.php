<?php

use yii\helpers\Html;

use app\assets\SisaiAsset;

SisaiAsset::register($this);

$this->registerJsFile('@web/js/chart.min.js');
$this->registerJsFile('@web/js/jspdf.umd.min.js');
$this->registerJsFile('@web/js/html2canvas.min.js');
$this->registerJsFile('@web/js/gerarpdf.js');

$this->title = 'Relatórios de Avaliação Docente';

$mensagem = false;

if($model->id_semestre < 23)
    $mensagem = [
        'id' => $model->id_semestre < 18 ? 'antigo' : 'online',
        'texto' => $model->id_semestre < 18 ? '*Este componente foi avaliado pela versão antiga do SISAI.' :
            '*Este componente foi avaliado por um questionário específico para modalidade online na versão antiga do SISAI.',
    ];
elseif($tipoQuestionario != 2)
    $mensagem = [
        'id' => $tipoQuestionario == 5 ? 'estagio' : 'online',
        'texto' => $tipoQuestionario == 5 ? '*Este componente foi avaliado por um questionário específico para estágio curricular.' :
            '*Este componente foi avaliado por um questionário específico para modalidade online.',
    ];
?>

<div class="sisai-relatorios-docente row" id="geraPDF">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_searchavaliacaodocente', ['model' => $model])?>

        <?php if($relatorio):?>

            <div class="bodyRelatorio">

                <?= $this->render('_tabelaavaliacaodocente', ['model' => $model, 'relatorio' => $relatorio, 'tipoQuestionario' => $tipoQuestionario])?>
                <?php if($relatorio->getTotalCount() > 0):?>
                    <div class="graficoRelatorio">
                        <?= $this->render('_graficoavaliacaodocente', ['model' => $model, 'relatorio' => $relatorio, 'tipoQuestionario' => $tipoQuestionario])?>
                    </div>
                    <?php if($mensagem):?>
                        <p id=<?=$mensagem['id']?> style="margin-left: 10px;">
                            <?=$mensagem['texto']?>
                        </p>
                    <?php endif;?>
                    <p id='dataRelatorio' style="text-align: right; margin-right: 10px;">
                        Relatório gerado em: <?=date('d/m/Y')?>.
                    </p>
                <?php endif;?>
            </div>
        <?php endif;?>
</div>
<?php if($relatorio && $relatorio->getTotalCount() > 0):?>
    <a onclick="gerarPDF(this);" class="btn btn-info btn-lg" style="margin-top:20px">Gerar PDF</a>
<?php endif;?>