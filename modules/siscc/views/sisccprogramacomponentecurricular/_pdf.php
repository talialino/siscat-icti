<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use app\assets\SisccAsset;
use app\components\SiscatPdfWidget;
SisccAsset::register($this);

$nome = "{$model->componente}.pdf";
//$brasaoUfba = Url::to('@web/images/brasao_ufba.jpg');
$brasaoUfba = "https://siscat.ims.ufba.br/siscat/images/brasao_ufba.jpg";

SiscatPdfWidget::begin([
    'nome' => $nome,
    'filename' => $nome,
    'cabecalho' => false,
    'marginTop' => 10,
    'rodape' => false,
    'modulo' => 'SISCC - Sistema de Componente Curricular',
    'title' => 'SISCC',
    'cssInLine' => '.summary, .btn-print{ display:none }'
]);

?>
<div class="visualizarPdf">
    <div style="border-top:1px solid black;border-bottom:1px solid black;padding:10px 0">
    <table border="0" width="100%">
        <tbody>
            <tr>
                <td width="15%" style="vertical-align:middle;text-align:center"><img src="<?=$brasaoUfba?>"></td>
                <td width="52%" style="font-size:15px;font-weight:bold;vertical-align:middle">UNIVERSIDADE FEDERAL DA BAHIA <br />INSTITUTO MULTIDISCIPLINAR EM SAÚDE <br />COORDENAÇÃO ACADÊMICA DE ENSINO</td>
                <td width="33%" style="font-size:15px;font-weight:bold;vertical-align:middle;text-align:center"><?=$model->situacao == 11 ? 'PROGRAMA DO COMPONENTE CURRICULAR' : '<span style="color: red;">PROGRAMA AGUARDANDO APROVAÇÃO</span>'?></td>
            </tr>
        </tbody>
    </table>
    </div>
    <h2 style="text-align: center; display: block; background: #555; color: white; padding:5px 0; font-size: 16px; font-family: Verdana;font-weight:bold;margin-top:40px">DADOS DE IDENTIFICA&Ccedil;&Atilde;O E ATRIBUTOS</h2>
    <table style="vertical-align:middle;margin-bottom:10px; text-align:center" class="table table-bordered" width="100%">
        <thead>
            <tr>
                <th style="border:1px solid #555;" width="15%">C&Oacute;DIGO</th>
                <th style="border:1px solid #555;" width="47%">NOME</th>
                <th style="border:1px solid #555;" width="38%">COLEGIADO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border:1px solid #555;vertical-align:middle"><?=$model->componenteCurricular->codigo_componente?></td>
                <td style="border:1px solid #555;vertical-align:middle"><?=$model->componente?></td>
                <td style="border:1px solid #555;vertical-align:middle"><?=$model->colegiado?></td>
            </tr>
        </tbody>
    </table>
    <table style="margin:10px 0" class="table table-bordered" width="100%">
        <thead>
            <tr>
                <th style="border:1px solid #555;vertical-align:middle; text-align:center" width="50%">MODALIDADE/SUBMODALIDADE</th>
                <th style="border:1px solid #555;vertical-align:middle; text-align:center" width="50%">PR&Eacute;-REQUISITO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border:1px solid #555;vertical-align:middle; text-align:center"><?=$model->componenteCurricular->modalidadeSubmodalidade?></td>
                <td style="border:1px solid #555;vertical-align:middle; text-align:center"><?=$model->componenteCurricular->prerequisitos?></td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered" style="text-align:center; margin:0" width="100%">
        <thead>
            <tr>
                <th style="border:1px solid #555;vertical-align:middle; text-align:center" colspan="5" width="45%">CARGA HOR&Aacute;RIA</th>
                <th style="border:1px solid #555;vertical-align:middle; text-align:center" colspan="4" width="35%">M&Oacute;DULO</th>
                <th style="border:1px solid #555;vertical-align:middle; text-align:center" width="20%">ANO DE VIG&Ecirc;NCIA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border:1px solid #555">T</td>
                <td style="border:1px solid #555">P</td>
                <td style="border:1px solid #555">E</td>
                <td style="border:1px solid #555">Ext</td>
                <td style="border:1px solid #555">Total</td>
                <td style="border:1px solid #555">T</td>
                <td style="border:1px solid #555">P</td>
                <td style="border:1px solid #555">E</td>
                <td style="border:1px solid #555">EXT</td>
                <td style="border:1px solid #555;vertical-align:middle" rowspan="2"><?=$model->semestreString?></td>
            </tr>
            <tr>
                <td style="border:1px solid #555"><?=$model->componenteCurricular->ch_teorica?></td>
                <td style="border:1px solid #555"><?=$model->componenteCurricular->ch_pratica?></td>
                <td style="border:1px solid #555"><?=$model->componenteCurricular->ch_estagio?></td>
                <td style="border:1px solid #555"><?=$model->componenteCurricular->ch_extensao?></td>
                <td style="border:1px solid #555"><?=$model->componenteCurricular->cargaHorariaTotal?></td>
                <td style="border:1px solid #555"><?=$model->componenteCurricular->modulo_teoria?></td>
                <td style="border:1px solid #555"><?=$model->componenteCurricular->modulo_pratica?></td>
                <td style="border:1px solid #555"><?=$model->componenteCurricular->modulo_estagio?></td>
                <td style="border:1px solid #555"><?=$model->componenteCurricular->modulo_extensao?></td>
            </tr>
        </tbody>
    </table>
    <h2 style="text-align: center; display: block; background: #555; color: white; padding:5px 0; font-size: 16px; font-family: Verdana;font-weight:bold;margin-top:40px">EMENTA</h2>
    <div style="text-align:justify"><?=$model->componenteCurricular->ementa?></div>
    <h2 style="text-align: center; display: block; background: #555; color: white; padding:5px 0; font-size: 16px; font-family: Verdana;font-weight:bold;margin-top:40px">OBJETIVOS</h2>
    <div style="margin-bottom:15px">    
        <div><strong>Objetivo Geral:</strong></div>
        <?=$model->objetivo_geral?>
    </div>
    <div>
        <span><strong> Objetivos Espec&iacute;ficos:</strong></span>
        <?=$model->objetivos_especificos?>
    </div>
    <h2 style="text-align: center; display: block; background: #555; color: white; padding:5px 0; font-size: 16px; font-family: Verdana;font-weight:bold;margin-top:40px">CONTE&Uacute;DO PROGRAM&Aacute;TICO</h2>
    <div class="conteudoProgramatico">
        <?=$model->conteudo_programatico?>
    </div>
    <h2 style="text-align: center; display: block; background: #555; color: white; padding:5px 0; font-size: 16px; font-family: Verdana;font-weight:bold;margin-top:40px">BIBLIOGRAFIA</h2>
    <div>
        <p><strong>Bibliografia B&aacute;sica:</strong></p></div>
        <?
            foreach($model->sisccProgramaComponenteCurricularBibliografias as $valor) {
                if($valor->tipo_referencia==1){
                    echo "<p>" .$valor->referencia ."</p>";
                }
            }
        ?>
    <div>
        <p><strong>Bibliografia Complementar:</strong></p>
        <?
            foreach($model->sisccProgramaComponenteCurricularBibliografias as $valor) {
                if($valor->tipo_referencia==2){
                    echo "<p>" .$valor->referencia ."</p>";
                }
            }
        ?>
    </div>
    <div>
        <p><strong>Sugest&atilde;o Bibliografia B&aacute;sica:</strong></p>
        <?
            foreach($model->sisccProgramaComponenteCurricularBibliografias as $valor) {
                if($valor->tipo_referencia==3){
                    echo "<p>" .$valor->referencia ."</p>";
                }
            }
        ?>
    </div>
    <div>
        <p><strong>Sugest&atilde;o Bibliografia Complementar:</strong></p>
        <?
            foreach($model->sisccProgramaComponenteCurricularBibliografias as $valor) {
                if($valor->tipo_referencia==4){
                    echo "<p>" .$valor->referencia ."</p>";
                }
            }
        ?>
    </div>
    <h2 style="text-align: center; display: block; background: #555; color: white; padding:5px 0; font-size: 16px; font-family: Verdana;font-weight:bold;margin-top:40px">DOCENTES RESPONSÁVEIS</h2>
    <div class="conteudoProgramatico">
        <ul>
            <? foreach($model->pessoas as $valor) {
                    echo "<li>" .$valor->nome."</li>";
                }
            ?>
        </ul>
    </div>

    <hr style="margin-bottom:80px;height:5px"/>
    <?php if($model->situacao == 11):?>
        <table border="0"  width="100%">
            <tbody>
                <tr>
                    <td width="50%" style="font-size:12px;vertical-align:middle;text-align:center">
                    ______________________________________<br />
                    Assinatura e Carimbo do Coordenador do Curso<br />
                    Programa aprovado pelo colegiado no dia <?=Yii::$app->formatter->format($model->data_aprovacao_colegiado,'date')?>
                    </td>
                    <td width="50%" style="font-size:12px;text-align:center; vertical-align:middle">
                    ______________________________________<br />
                    Assinatura e Carimbo do Coordenador Acadêmico<br />
                    Programa aprovado em Reunião plenária do dia <?=Yii::$app->formatter->format($model->data_aprovacao_coordenacao,'date')?>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php else:?>
        <p style="color:red; font-size:18px; text-align:center;">Esta cópia não vale como documento oficial.</p>
    <?php endif;?>
</div>
<?php SiscatPdfWidget::end();?>