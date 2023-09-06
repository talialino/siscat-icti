<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use app\assets\SisligaAsset;
use app\components\SiscatPdfWidget;
SisligaAsset::register($this);

$formatter = \Yii::$app->formatter;
$caracteres  = ["\r\n", "\n","\t", "\r"];
$nome = strip_tags(trim(str_replace($caracteres, "", $model->nome))).".pdf";
//$brasaoUfba = Url::to('@web/images/brasao_ufba.jpg');
$brasaoUfba = "https://siscat.ims.ufba.br/siscat/images/brasao_ufba.jpg";


SiscatPdfWidget::begin([
    'nome' => $nome,
    'filename' => $nome,
    'cabecalho' => false,
    'marginTop' => 10,
    'rodape' => false,
    'modulo' => 'SISLIGA - Sistema de Acompanhamento de Ligas Acadêmicas',
    'title' => 'SISLIGA',
    'cssInLine' => '.summary, .btn-print{ display:none }'
]);

?>
<div class="visualizarPdf">
<div style="border-top:1px solid black;border-bottom:1px solid black;padding:10px 0">
    <table border="0" width="100%">
        <tbody>
            <tr>
                <td width="15%" style="vertical-align:middle;text-align:center"><img src="<?=$brasaoUfba?>"></td>
                <td width="90%" style="font-size:15px;font-weight:bold;vertical-align:middle">UNIVERSIDADE FEDERAL DA BAHIA <br />INSTITUTO MULTIDISCIPLINAR EM SAÚDE <br />SISLIGA - Sistema de Acompanhamento de Ligas Acadêmicas</td>
            </tr>
        </tbody>
    </table>
    </div>

<div class="clearfix" style="margin-bottom:10px"></div>
<div>
<table style="margin:10px 0" class="table table-bordered" width="100%">
<tbody>
  <tr>
    <td width="30%"><strong>Nome</strong></td>
    <td width="70%"><?=$model->nome?></td>
  </tr>
  <tr>
    <td width="30%"><strong>Responsável</strong></td>
    <td width="70%"><?=$model->pessoa->nome?></td>
  </tr>
  <tr>
    <td width="30%"><strong>Situação</strong></td>
    <td width="70%"><?=$model->situacaoString?></td>
  </tr>
  <tr>
    <td width="30%"><strong>Área de conhecimento</strong></td>
    <td width="70%"><?=$model->area_conhecimento?></td>
  </tr>
  <tr>
    <td width="30%"><strong>Locais de atuação</strong></td>
    <td width="70%"><?=$model->local_atuacao?></td>
  </tr>
</tbody>
</table>

<h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Resumo</h2>
    <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->resumo)?></p>

<h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Equipe Executora</h2>
<table style="margin:10px 0" class="table table-bordered" width="100%">
<thead>
  <tr>
    <th>Nome</th>
    <th>Instituição</th>
    <th>Função</th>
    <th>Carga Horária</th>
  </tr>
</thead>
<tbody>
  <?
    foreach($model->sisligaLigaIntegrantes as $valor) {
        echo '<tr>';
            echo "<td>" .$valor->nome ."</td>";
            echo "<td>" .$valor->instituicao ."</td>";
            echo "<td>" .$valor->funcao ."</td>";
            echo "<td>" .$valor->carga_horaria ."</td>";
        echo '</tr>';
    }
?>
</tbody>
</table > 
</div>
</div>
</div>
<?php SiscatPdfWidget::end();?>