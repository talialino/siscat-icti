<?php

use yii\helpers\Html;
//use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use app\assets\SispitAsset;
use app\modules\sispit\models\ResumoCargaHoraria;
use app\components\SiscatPdfWidget;

SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPlanoRelatorio */

$formatter = \Yii::$app->formatter;

$data_homologacao = ($pit_rit ? $model->data_homologacao_cac_rit : $model->data_homologacao_cac_pit);
$data_homologacao = $formatter->asDate($data_homologacao, 'short');
$data_preenchido = ($pit_rit ? $model->data_preenchimento_rit : $model->data_preenchimento_pit);
$data_preenchido = $formatter->asDate($data_preenchido, 'short');
$this->title = $pit_rit ? "Relatório Individual de Trabalho" : "Plano Individual de Trabalho";
$nome = ($pit_rit ? "RIT" : "PIT")." - {$model->pessoa->nome}.pdf";

SiscatPdfWidget::begin([
    'nome' => $nome,
    'filename' => $nome,
    'modulo' => 'SISPIT - Sistema de PIT/RIT',
    'title' => $this->title,
    'cssInLine' => '.summary, .btn-print{ display:none }'
]);
?>
<div class="sispit-plano-relatorio-visualizar">

    <h2 style="text-align:center; font-size:24px;margin:0; padding:-10px 0 0 0"><?= Html::encode($this->title)." - ". $model->ano.($model->ehRitParcial() ? '.1':'') ?></h2>
    <hr style="margin-bottom:10px" />
    <div style="text-align:right;">
        <span style="font-size:12px">Data de Homologação CAC: <?=$data_homologacao?></span>
    </div>
    <?= $this->render('_pdf.php', $model->planoRelatorioToArray($pit_rit));?>
    <div style="text-align:center; width:100%;padding-top:50px">
        <span>_________________________________________________________</span><br>
        <span>Assinatura</span><br>
        <span style="font-size:12px">Preenchido em <?=$data_preenchido?></span>
    </div>
</div>
<?php SiscatPdfWidget::end();?>