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
//$brasaoUfba = Url::to('@web/images/brasao_ufba.jpg'); Problema com Https
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
    <h1 style="display: block; padding:5px 0; font-size: 20px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:10px; margin-bottom:30px;border-bottom:1px dotted #ccc;text-align:center"><?=$model->nome?></h1>
    <table style="margin:10px 0" class="table table-bordered" width="100%">
    <tbody>
    <tr>
        <td width="20%"><strong>Vigência</strong></td>
        <td width="30%"><?=$model->situacaoLigaString?></td>
        <td width="20%"><strong>Situação</strong></td>
        <td width="30%"><?=$model->situacaoString?></td>
    </tr>
    <tr>
        <td width="20%"><strong>Data início</strong></td>
        <td width="30%"><?=$model->data_inicio?></td>
        <td width="20%"><strong>Data Fim</strong></td>
        <td width="30%"><?=$model->data_fim?></td>
    </tr>
    <tr>
        <td width="20%"><strong>Data de aprovação</strong></td>
        <td width="30%"><?=$model->data_aprovacao_comissao?></td>
        <td width="20%"><strong>Data de homologação</strong></td>
        <td width="30%"><?=$model->data_homologacao_congregacao?></td>
    </tr>
    <tr>
        <td width="20%"><strong>Número da Sessão</strong></td>
        <td width="30%"><?=$model->sessao_congregacao?></td>
        <td width="20%"><strong>Tipo da Sessão</strong></td>
        <td width="30%"><?=$model->tipoSessaoCongregacao?></td>
    </tr>
    </tbody>
    </table>

    <h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Atividades</h2>
        <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->atividades)?></p>

    <h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Prestação de Contas</h2>
    <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->prestacao_contas)?></p>

    <h2 style="display: block; padding:5px 0; font-size: 18px;font-weight:bold font-family: Verdana;font-weight:bold;margin-top:25px; border-bottom:3px solid #444">Considerações Finais</h2>
    <p style="padding:15px; text-align: justify;"><?=$formatter->asNtext($model->consideracoes_finais)?></p>

    </div>
    </div>
</div>
<?php SiscatPdfWidget::end();?>