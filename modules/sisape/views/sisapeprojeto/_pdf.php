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
$caracteres  = ["\r\n", "\n","\t", "\r"];
$nome = strip_tags(trim(str_replace($caracteres, "", $model->titulo))).".pdf";
//$brasaoUfba = Url::to('@web/images/brasao_ufba.jpg','https');
$brasaoUfba = "https://siscat.ims.ufba.br/siscat/images/brasao_ufba.jpg";


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
<table style="margin:10px 0" class="table table-bordered" width="100%">
<tbody>
  <tr>
    <td width="18%"><strong>Título</strong></td>
    <td width="45%"><?=$model->titulo?></td>
    <td width="17%"><strong>Número da Proposta</strong></td>
    <td width="20%"><?=$model->numero?></td>
  </tr>
  <tr>
    <td width="18%"><strong>Coordenador(a)</strong></td>
    <td width="45%"><?=$model->pessoa->nome?></td>
    <td width="17%"><strong>Tipo Projeto</strong></td>
    <td width="20%"><?=$model->tipoProjeto?></td>
  </tr>
  <?if($model->tipo_projeto == $model::EXTENSAO) {?>
    <tr>       
    <td><strong>Tipo de Extensão</strong></td>
    <td colspan="3"><?=$model->tipoExtensao?></td> 
  </tr>
  <?}?>
  <tr>
    <td><strong>Data Inicial</strong></td>
    <td><?=$formatter->asDate($model->data_inicio, 'dd/MM/yyyy')?></td>
    <td><strong>Data Final</strong></td>
    <td><?=$formatter->asDate($model->data_fim, 'dd/MM/yyyy')?></td>
  </tr>
  <tr>
    <td><strong>Área do Conhecimento</strong></td>
    <td><?=$model->area_atuacao?></td>
    <td><strong>Autoriza veiculação do resumo no site IMS</strong></td>
    <td><?=$model->disponivel_site ? 'Sim' : 'Não' ?></td>
  </tr>
  <?if($model->tipo_projeto == $model::PESQUISA) {?>
    <tr>
        <td><strong>Submetido ao Comitê de Ética (se for o caso)</strong></td>
        <td><?=$model->submetido_etica? 'Sim' : 'Não' ?></td>
        <td><strong>Local da Execução</strong></td>
        <td><?=$model->local_execucao?></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Parcerias</strong></td>
    </tr>
    <tr>
        <td colspan="4"><?=$model->parcerias?></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Infraestrutura Necessária e Material de Consumo</strong></td>
    </tr>
    <tr>
        <td colspan="4"><?=$model->infraestrutura?></td>
    </tr>
  <?}?>
</tbody>
</table>

<h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Resumo</h2>
    <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->resumo)?></p>
<?if($model->tipo_projeto == $model::PESQUISA) {?>
    <h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Introdução</h2>
        <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->introducao)?></p>
    <h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Justificativa</h2>
        <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->justificativa)?></p>
    <h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Objetivos</h2>
        <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->objetivos)?></p>
    <h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Metodologia</h2>
        <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->metodologia)?></p>
    <h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Resultados Esperados</h2>
        <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->resultados_esperados)?></p>
    <h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Orçamentos</h2>
        <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->orcamento)?></p>
    <h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Referências</h2>
        <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->referencias)?></p>
    
<?}?>



<h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Financiamentos</h2>
<table style="margin:10px 0" class="table table-bordered" width="100%">
<thead>
  <tr>
    <th>Origem</th>
    <th>Valor</th>
  </tr>
</thead>
<tbody>
  <?
    foreach($model->sisapeFinanciamentos as $valor) {
        echo '<tr>';
            echo "<td>" .$valor->origem ."</td>";
            echo "<td>" .$valor->valor ."</td>";
        echo '</tr>';
    }
?>
</tbody>
</table>
<h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Equipe Executora</h2>
<table style="margin:10px 0" class="table table-bordered" width="100%">
<thead>
  <tr>
    <th>Nome</th>
    <th>Vínculo</th>
    <th>Função</th>
    <th>Carga Horária</th>
  </tr>
</thead>
<tbody>
  <?
    foreach($model->sisapeProjetoIntegrantes as $valor) {
        echo '<tr>';
            echo "<td>" .$valor->nome ."</td>";
            echo "<td>" .$valor->vinculoString ."</td>";
            echo "<td>" .$valor->funcao ."</td>";
            echo "<td>" .$valor->carga_horaria ."</td>";
        echo '</tr>';
    }
?>
</tbody>
</table > 
<h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Cronograma</h2>
<table style="margin:10px 0" class="table table-bordered" width="100%">
<thead>
  <tr>
    <th>Descrição</th>
    <th>Data Início</th>
    <th>Data Final</th>
  </tr>
</thead>
<tbody>
  <?
    foreach($model->sisapeAtividades as $valor) {
        echo '<tr>';
            echo "<td>" .$valor->descricao_atividade ."</td>";
            echo "<td>" .$formatter->asDate($valor->data_inicio, 'dd/MM/yyyy') ."</td>";
            echo "<td>" .$formatter->asDate($valor->data_fim, 'dd/MM/yyyy') ."</td>";
        echo '</tr>';
    }
?>
</tbody>
</table>
</div>
</div>
</div>
<?php SiscatPdfWidget::end();?>