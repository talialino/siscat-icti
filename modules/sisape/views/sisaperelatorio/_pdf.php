<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use app\assets\SisapeAsset;
use app\components\SiscatPdfWidget;
SisapeAsset::register($this);

$formatter = \Yii::$app->formatter;
$nome = "relatorio{$model->titulo}.pdf";
$brasaoUfba = Url::to('@web/images/brasao_ufba.jpg');

SiscatPdfWidget::begin([
    'nome' => $nome,
    'filename' => $nome,
    'cabecalho' => false,
    'marginTop' => 10,
    'rodape' => false,
    'modulo' => 'SISAPE - Sistema de Acompanhamento de Projetos de Pesquisa e Extensão',
    'title' => 'SISAPE',
    'cssInLine' => '.summary, .btn-print{ display:none }'
]);

?>
<div class="visualizarPdf">
<div style="border-top:1px solid black;border-bottom:1px solid black;padding:10px 0">
    <table border="0" width="100%">
        <tbody>
            <tr>
                <td width="15%" style="vertical-align:middle;text-align:center"><img src="<?=$brasaoUfba?>"></td>
                <td width="90%" style="font-size:15px;font-weight:bold;vertical-align:middle">UNIVERSIDADE FEDERAL DA BAHIA <br />INSTITUTO MULTIDISCIPLINAR EM SAÚDE <br />SISAPE - Sistema de Acompanhamento de Projetos de Pesquisa e Extensão</td>
            </tr>
        </tbody>
    </table>
    </div>

<div class="clearfix" style="margin-bottom:10px"></div>
<div>
<h3 style="text-align:center">Relatório de Execução de Projeto</h3>
<table style="margin:10px 0" class="table table-bordered" width="100%">
<tbody>
  <tr>
    <td width="17%"><strong>Título</strong></td>
    <td width="84%" colspan="3"><?=$model->projetoTitulo?></td>
  </tr>
  <tr>
    <td width="17%"><strong>Situação do Projeto</strong></td>
    <td width="84%" colspan="3"><?=$model->situacaoProjeto?></td>
  </tr>
  <tr>
    <td width="17%"><strong>Justificativa</strong></td>
    <td width="84%" colspan="3"><?=$model->justificativa?></td>
  </tr>
  <tr>
    <td><strong>Data Relatório</strong></td>
    <td width="84%" colspan="3"><?=$formatter->asDate($model->data_relatorio, 'dd/MM/yyyy')?></td>
  </tr>
  <tr>
    <td width="17%"><strong>Número de alunos orientados</strong></td>
    <td width="33%"><?=$model->alunos_orientados?></td>
    <td width="17%"><strong>Número de resumos publicados</strong></td>
    <td width="33%"><?=$model->resumos_publicados?></td>
  </tr>
  <tr>
    <td width="17%"><strong>Número de artigos publicados</strong></td>
    <td width="33%"><?=$model->artigos_publicados?></td>
    <td width="17%"><strong>Número de artigos aceitos para publicação</strong></td>
    <td width="33%"><?=$model->artigos_aceitos?></td>
  </tr>r
  <tr>
    <td width="17%"><strong>Relatório técnico apresentado à agência de fomento</strong></td>
    <td width="33%"><?=$model->relatorio_agencia ? 'Sim' : 'Não' ?></td>
    <td width="17%"><strong>Depósito de Patente</strong></td>
    <td width="33%"><?=$model->deposito_patente ? 'Sim' : 'Não' ?></td>
  </tr>
  <tr>
    <td width="17%"><strong>Nº da Sessão Congregação</strong></td>
    <td width="33%"><?=$model->sessao_congregacao?></td>
    <td width="17%"><strong>Tipo de Sessão da Congregação</strong></td>
    <td width="33%"><?=$model->tipoSessaoCongregacao?></td>
  </tr>
  <tr>
      <td colspan="4"><strong>Outros Indicadores</strong></td>
  </tr>
  <tr>
      <td colspan="4"><?=$model->outros_indicadores?></td>
  </tr>
  <tr>
      <td colspan="4"><strong>Considerações Finais</strong></td>
  </tr>
  <tr>
      <td colspan="4"><?=$model->consideracoes_finais?></td>
    </tr>
</tbody>
</table>
</div>
</div>
</div>
<?php SiscatPdfWidget::end();?>