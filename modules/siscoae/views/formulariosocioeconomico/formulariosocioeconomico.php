<?php

use yii\helpers\Url;
use app\components\SiscatPdfWidget;


$nome = 'Formulario_socioeconomico_'.str_pad($model[0],3,"0", STR_PAD_LEFT).'.pdf';
$brasaoUfba = "https://siscat.ims.ufba.br/siscat/images/brasao_ufba.jpg";
SiscatPdfWidget::begin([
  'nome' => $nome,
  'filename' => $nome,
  'cabecalho' => false,
  'marginTop' => 10,
  'rodape' => false,
  'modulo' => 'SISCOAE',
  'title' => 'SISCOAE',
  'cssInLine' => '.summary, .btn-print{ display:none }',
]);

function converterMoeda($valor)
{
  if(is_numeric(str_replace(',','.',$valor)))
    return Yii::$app->formatter->asCurrency(str_replace(',','.',$valor));
  return $valor;
}

?>
<div class="formularioPdf">
<div style="border-top:1px solid black;border-bottom:1px solid black;padding:10px 0">
    <table border="0" width="100%">
        <tbody>
            <tr>
                <td width="10%" style="vertical-align:middle;text-align:center"></td>
                <td width="90%" style="font-size:15px;font-weight:bold;vertical-align:middle">UNIVERSIDADE FEDERAL DA BAHIA <br />PRÓ-REITORIA DE AÇÕES AFIRMATIVAS E ASSISTÊNCIA ESTUDANTIL - PROAE <br />COORDENAÇÃO DE AÇÕES AFIRMATIVAS E ASSISTÊNCIA ESTUDANTIL - COAE/IMS</td>
            </tr>
        </tbody>
    </table>
    </div>

<h2 style="text-align:center">Formulário Socioeconômico - ID <?=$model[0]?> </h2>
<table style="margin:10px 0" class="table table-bordered" width="100%">
<tr><td colspan=6 style="background: #e1dfdf;text-align: center;font-weight: bold;color:black;">IDENTIFICAÇÃO E DADOS DO/A ESTUDANTE</td></tr>
  <tr>
    <td colspan=6><strong>Nome civil completo: </strong> <span> <?=$model[91]?></span></td>
  </tr>
  <tr>
    <td colspan=6><strong>Nome social: </strong> <span> <?=$model[92]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Data de nascimento: </strong> <span> <?=Yii::$app->formatter->asDate($model[93])?> </span></td>
    <td colspan=2><strong>CPF: </strong> <span> <?=$model[110]?> </span></td>
    <td colspan=2><strong>RG: </strong> <span> <?=$model[111]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Sexo: </strong> <span> <?=$model[105]?> </span></td>
    <td colspan=2><strong>Identidade de gênero: </strong> <span> <?=$model[106] == 'Outros' ? $model[107] : $model[106]?> </span></td>
    <td colspan=2><strong>Orientação sexual: </strong> <span> <?=$model[108] == 'Outros' ? $model[109] : $model[108]?></span></td>
  </tr>
  
  <tr>
    <td colspan=3><strong>Telefone: </strong> <span> <?="{$model[119]} {$model[120]}"?> </span></td>
    <td colspan=3><strong>E-mail: </strong> <span> t<?=$model[118]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Nacionalidade: </strong> <span> <?=$model[94] == 'Outros' ? $model[95] : $model[94]?> </span></td>
    <td colspan=2><strong>Naturalidade: </strong> <span> <?=$model[96] == 'Outros' ? $model[97] : $model[96]?> </span></td>
    <td colspan=2><strong>Estado onde nasceu: </strong> <span> <?=$model[98] == 'Outros' ? $model[99] : $model[98]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Cor: </strong> <span> <?=$model[101]?> </span></td>
    <td colspan=4><strong>Pertence a algum desses povos/comunidades?: </strong> <span> <?=$model[103] == 'Outros' ? $model[104] : $model[103]?> </span></td>
  </tr>
  <tr>
    <td colspan=4><strong>Endereço atual do/a estudante: </strong> <span> <?=$model[112]?> </span></td>
    <td colspan=2><strong>CEP da atual residência do/a estudante: </strong> <span> <?=$model[117]?> </span></td>
  </tr>
  <tr>
    <td colspan=3><strong>Bairro da atual residência do/a estudante: </strong> <span> <?=$model[113] == 'Outros' ? $model[114] : $model[113]?> </span></td>
    <td colspan=3><strong>Cidade de residência atual do/a estudante: </strong> <span> <?=$model[115] == 'Outros' ? $model[116] : $model[115]?> </span></td>
  </tr>
  <tr>
    <td colspan=6><strong>Em caso de emergência devemos contatar </strong> <br>
      <span> Nome: </span> <u><?=$model[121]?></u><br>
      <span> Grau de Parentesco/Tipo de Vínculo: </span><u><?=$model[122]?></u><br>
      <span> Telefone com DDD: </span><u><?=$model[123]?></u><br>
    </td>
  </tr>
</table>

<table style="margin:20px 0" class="table table-bordered" width="100%">
<tr><td colspan=6 style="background: #e1dfdf;text-align: center;font-weight: bold;color:black;">DADOS ACADÊMICOS</td></tr>
  <tr>
    <td colspan=2><strong>Matrícula: </strong> <span> <?=$model[64]?> </span></td>
    <td colspan=2><strong>Curso: </strong> <span> <?=$model[65]?> </span></td>
    <td colspan=2><strong>Provável concluinte em 2022.2?: </strong> <span> <?=$model[66]?> </span></td>
  </tr>
  <tr>
    <td colspan=6><strong>Quantidade de disciplinas em 2022.2: </strong><br>
      <span> On line (remotas): <?=$model[67]?></span> <br>
      <span> Presenciais: <?=$model[68]?></span> <br>
    </td>
  </tr>
  <tr>
    <td colspan=6><strong>Modalidade de ingresso na UFBA: </strong><span> <?=$model[69]?></span>
    </td>
  </tr>
  <tr>
    <td colspan=3><strong>Concluiu outro curso superior? </strong> <span> <?=$model[70]?> </span></td>
    <td colspan=3><strong>Cursou o ensino médio em escola: </strong> <span> <?=$model[71]?> </span></td>
  </tr>
  <tr>
    <td colspan=6><strong>Sobre as condições de acesso à internet: </strong><br>
       Tem acesso à internet?  <u><?=$model[72]?></u> <br>
       Há disponibilidade de oferta do serviço de acesso à internet no local onde pretende realizar as atividades deste semestre? <u><?=$model[73]?></u><br>
       Compartilha o uso da internet com outro(s) domicílio(s)?  <u><?=$model[74]?></u> <br>
    </td>
  </tr>
  <tr>
    <td colspan=6><strong>Forma de acesso à internet: </strong><span><?=$model[75] == 'Outros' ? $model[76] : $model[75]?></span>
    </td>
  </tr>
  <tr>
    <td colspan=6><strong>Qual(is) equipamento(s) possui para realização das atividades acadêmicas durante o semestre 2022.1? </strong><br>
      <?php if($model[77] == 'Sim'):?><span> Computador </span> <br><?php endif;?>
      <?php if($model[78] == 'Sim'):?><span> Smartphone </span> <br><?php endif;?>
      <?php if($model[79] == 'Sim'):?><span> Notebook </span> <br><?php endif;?>
      <?php if($model[80] == 'Sim'):?><span> Tablet </span> <br><?php endif;?>
      <?php if($model[81] == 'Sim'):?><span> Não possuo equipamento </span> <br><?php endif;?>
      <?php if($model[82] == 'Sim'):?><span> Outros </span> <br><?php endif;?>
    </td>
  </tr>
  <tr>
    <td colspan=6><strong>O(s) equipamento(s) indicado(s) na questão anterior é(são) de uso individual? </strong><span> <?=$model[83]?></span>
    </td>
  </tr>
  <tr>
    <td colspan=6><strong>Necessita de apoio para realização de atividades acadêmicas? </strong><br>
    <?php if($model[84] == 'Sim'):?><span> Não </span> <br><?php endif;?>
    <?php if($model[85] == 'Sim'):?><span> Material em braile </span> <br><?php endif;?>
    <?php if($model[86] == 'Sim'):?><span> Tradutor LIBRAS </span> <br><?php endif;?>
    <?php if($model[87] == 'Sim'):?><span> Transcritor </span> <br><?php endif;?>
    <?php if($model[88] == 'Sim'):?><span> Suporte de monitor </span> <br><?php endif;?>
    <?php if($model[89] == 'Sim'):?><span> Mobília adaptada </span> <br><?php endif;?>
    <?php if($model[90] == 'Sim'):?><span> Estrutura física adaptada </span> <br><?php endif;?>

    </td>
  </tr>
</table>

<table style="margin:20px 0" class="table table-bordered" width="100%">
<tr><td colspan=6 style="background: #e1dfdf;text-align: center;font-weight: bold;color:black;">SOLICITAÇÃO</td></tr>
  <tr>
    <td colspan=2><strong>Tipo da solicitação </strong><br>
      <?php if($model[6] == 'Sim'):?><span> Auxílio a Estudantes com Necessidades Educativas Especiais </span> <br><?php endif;?>
      <?php if($model[7] == 'Sim'):?><span> Auxílio Alimentação </span> <br><?php endif;?>
      <?php if($model[8] == 'Sim'):?><span> Auxílio de Apoio à Inclusão Digital </span> <br><?php endif;?>
      <?php if($model[9] == 'Sim'):?><span> Auxílio Especial de Permanência </span> <br><?php endif;?>
      <?php if($model[10] == 'Sim'):?><span> Auxílio Transporte </span> <br><?php endif;?>
      <?php if($model[11] == 'Sim'):?><span> Cadastro Geral </span> <br><?php endif;?>
      <?php if($model[12] == 'Sim'):?><span> Serviço de Alimentação </span> <br><?php endif;?>
    </td>
  </tr>
  <tr>
    <td colspan=2><strong>Para qual(is) finalidades deseja utilizar o Auxílio Especial para Permanencia?  </strong><br>
      <?php if($model[13] == 'Sim'):?><span> Alimentação </span> <br><?php endif;?>
      <?php if($model[14] == 'Sim'):?><span> Atenção à saúde </span> <br><?php endif;?>
      <?php if($model[15] == 'Sim'):?><span> Inclusão digital </span> <br><?php endif;?>
      <?php if($model[16] == 'Sim'):?><span> Moradia </span> <br><?php endif;?>
      <?php if($model[17] == 'Sim'):?><span> Transporte </span> <br><?php endif;?>
      <?php if($model[18] == 'Sim'):?><span> Outros </span> <br><?php endif;?>
    </td>
  </tr>
  <tr>
    <td colspan=2><strong>Qual equipamento será objeto da aquisição/melhoria? </strong><br>
      <?php if($model[19] == 'Sim'):?><span> Computador </span> <br><?php endif;?>
      <?php if($model[20] == 'Sim'):?><span> E-reader </span> <br><?php endif;?>
      <?php if($model[21] == 'Sim'):?><span> Notebook </span> <br><?php endif;?>
      <?php if($model[22] == 'Sim'):?><span> Tablet </span> <br><?php endif;?>
      <?php if($model[23] == 'Sim'):?><span> Smartphone </span> <br><?php endif;?>
      <?php if($model[24] == 'Sim'):?><span> Outros </span> <br><?php endif;?>
    </td>
  </tr>
  <tr>
    <td colspan=2><strong>Voce pretende utilizar o Auxílio de Apoio à Inclusao Digital para: </strong><br>
      <?php if($model[25] == 'Sim'):?><span> Aquisição </span> <br><?php endif;?>
      <?php if($model[26] == 'Sim'):?><span> Melhoria </span> <br><?php endif;?>
    </td>
  </tr>
  <tr>
    <td><strong>É estudante com necessidades educativas especiais? </strong><span> <?=$model[27]?> </span> </td>
    <td><strong>Especifique: </strong><span><?=$model[28] == 'Outros' ? $model[29] : $model[28]?></span></td>
  </tr>
  <tr>
    <td><strong>Tipo de vaga: </strong>
      <span> <?=$model[30]?> </span>
      <?php if($model[30] == 'Vagas Reservadas'):?><span> - <?=$model[31]?> </span> <br><?php endif;?>
    </td>
    <td><strong>Já foi contemplada/o com benefícios da PROAE? </strong> <span> <?=$model[32]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Com qual(is) benefício(s) foi contemplado/a? </strong><br>
    <?php if($model[33] == 'Sim'):?><span> APOIO PARA ACESSO À INTERNET (CHIP) </span> <br><?php endif;?>
    <?php if($model[34] == 'Sim'):?><span> AUXÍLIO A PESSOA COM NECESSIDADES EDUCATIVAS ESPECIAIS </span> <br><?php endif;?>
    <?php if($model[35] == 'Sim'):?><span> AUXÍLIO CRECHE </span> <br><?php endif;?>
    <?php if($model[36] == 'Sim'):?><span> AUXÍLIO DE APOIO À INCLUSÃO DIGITAL (AQUISIÇÃO/MELHORIA DE EQUIPAMENTOS) </span> <br><?php endif;?>
    <?php if($model[37] == 'Sim'):?><span> PROGRAMA MORADIA (AUXÍLIO MORADIA OU SERVIÇO DE RESIDÊNCIA UNIVERSITÁRIA - SRU) </span> <br><?php endif;?>
    <?php if($model[38] == 'Sim'):?><span> AUXÍLIO TRANSPORTE </span> <br><?php endif;?>
    <?php if($model[39] == 'Sim'):?><span> SERVIÇO CRECHE </span> <br><?php endif;?>
    <?php if($model[40] == 'Sim'):?><span> SERVIÇO DE ALIMENTAÇÃO </span> <br><?php endif;?>
    <?php if($model[41] == 'Sim'):?><span> AUXÍLIO ESPECIAL PARA PERMANÊNCIA </span> <br><?php endif;?>
    </td>
  </tr>
  <tr>
    <td colspan=2><strong>Tem familiares cadastrados na PROAE?</strong><span> <?=$model[42]?></span></td>
  </tr>
</table>

<table style="margin:20px 0" class="table table-bordered" width="100%">
<tr><td colspan=2 style="background: #e1dfdf;text-align: center;font-weight: bold;color:black;">FILIAÇÃO</td></tr>
  <tr>
    <td colspan=2><strong>Nome completo da mãe: </strong> <span> <?=$model[43]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Pai conhecido? </strong> <span> <?=$model[44]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Nome completo do pai: </strong> <span> <?=$model[45]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Sobre seus pais:</strong><br>
      <span> Mãe: </span> <u><?=$model[46]?></u><br>
      <span> Pai: </span> <u><?=$model[47]?></u><br>
    </td>
  </tr>
  <tr>
    <td colspan=2><strong>Sobre a mãe </strong><br>
      <ul>
        <li>Escolaridade: <u><?=$model[48]?></u> </li>
        <li>Estado Civil: <u><?=$model[49]?></u></li>
        <li>Ocupação laboral: <u><?=$model[50]?></u></li>
        <li>Origem dos rendimentos: <u><?=$model[51]?></u></li>
        <li>Endereço: <u><?=$model[52]?></u></li>
        <li>Estado em que vive: <u><?=$model[53]?></u></li>
        <li>Zona de residencia (urbana/rural): <u><?=$model[54]?></u></li>
        <li>Telefone para contato: <u><?=$model[55]?></u></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan=2><strong>Sobre a pai </strong><br>
      <ul>
        <li>Escolaridade: <u><?=$model[56]?></u> </li>
        <li>Estado Civil: <u><?=$model[57]?></u></li>
        <li>Ocupação laboral: <u><?=$model[58]?></u></li>
        <li>Origem dos rendimentos: <u><?=$model[59]?></u></li>
        <li>Endereço: <u><?=$model[60]?></u></li>
        <li>Estado em que vive: <u><?=$model[61]?></u></li>
        <li>Zona de residencia (urbana/rural): <u><?=$model[62]?></u></li>
        <li>Telefone para contato: <u><?=$model[63]?></u></li>
      </ul>
    </td>
  </tr>
</table>

<table style="margin:20px 0" class="table table-bordered" width="100%">
<tr><td colspan=3 style="background: #e1dfdf;text-align: center;font-weight: bold;color:black;">IDENTIFICAÇÃO E DADOS SOCIOECONÔMICOS DA FAMÍLIA</td></tr>
  <tr>
    <td colspan=3><strong>Quantas pessoas, incluindo você, compõem seu núcleo familiar? </strong> <span> <?=$model[124]?> </span></td>
  </tr>
  <?php for($i = 0; $i< $model[124]; $i++): ?>
  <tr>
    <td colspan=3><strong><?= $i == 0 ? 'ESTUDANTE UFBA SOLICITANTE DO BENEFÍCIO': 'Sobre Familiar '.(1 + $i)?></strong><br>
      <ul>
        <li>Nome Completo: <u><?=$model[125 + (9 * $i)]?></u> </li>
        <li>Grau de Parentesco: <u><?=$model[126 + (9 * $i)]?></u></li>
        <li>Estado Civil: <u><?=$model[127 + (9 * $i)]?></u></li>
        <li>Escolaridade: <u><?=$model[128 + (9 * $i)]?></u></li>
        <li>Estuda? <u><?=$model[129 + (9 * $i)]?></u></li>
        <li>Trabalha? <u><?=$model[130 + (9 * $i)]?></u></li>
        <li>Tem Carteira de Trabalho e Previdência Social assinada? <u><?=$model[131 + (9 * $i)]?></u></li>
        <li>Ocupação(ões)/Origem da Renda: <u><?=$model[132 + (9 * $i)]?></u></li>
        <li>Renda bruta do mês anterior ao Edital: <u><?=converterMoeda($model[133 + (9 * $i)])?></u></li>
      </ul>
    </td>
  </tr>
  <?php endfor;?>
  <tr>
    <td colspan=3><strong>Neste núcleo há: </strong><br>
      <ul>
        <li>Gestante(s): <u><?=$model[215]?></u> </li>
        <li>Lactantes: <u><?=$model[216]?></u></li>
        <li>Lactentes: <u><?=$model[217]?></u></li>
        <li>Pessoa idosa: <u><?=$model[218]?></u></li>
        <li>Pessoa que debilitadas, acamadas ou dependentes de cuidados: <u><?=$model[219]?></u></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan=3><strong>Na sua família há pessoa(s) que: </strong><br>
      <ul>
        <li>Possui diagnóstico de doença crônica e/ou degenerativa na família: <u><?=$model[220]?></u> </li>
        <li>
          Possui diagnóstico de doença grave e/ou redutora de capacidade: <u><?=$model[221]?></u>
          <?php if($model[221] == 'Sim'):?><ul><li><?=$model[224]?></li></ul><?php endif;?>
        </li>
        <li>
          Tem dependência de álcool ou outras drogas: <u><?=$model[222]?></u>
          <?php if($model[222] == 'Sim'):?><ul><li><?=$model[225]?></li></ul><?php endif;?>
        </li>
        <li>
          Possui diagnóstico de transtorno mental (ex.: depressão, crise de ansiedade, esquizofrenia, etc.): <u><?=$model[223]?></u>
          <?php if($model[223] == 'Sim'):?><ul><li><?=$model[226]?></li></ul><?php endif;?>
        </li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan=3><strong>Na sua família há alguém que: </strong><br>
      <ul>
        <li>Está em regime de reclusão? <u><?=$model[227]?></u> </li>
        <li>Esteve em regime de reclusão? <u><?=$model[228]?></u></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan=3><strong>Quem é o(a) principal mantenedor(a) de sua família (a pessoa que mais contribui com a renda para a subsistência familiar)?</strong><br>
      <ul>
        <?php if($model[229] == 'Sim'):?><li>Você mesma(o) - estudante</li><?php endif;?>
        <?php if($model[230] == 'Sim'):?><li>Mãe</li><?php endif;?>
        <?php if($model[231] == 'Sim'):?><li>Pai</li><?php endif;?>
        <?php if($model[232] == 'Sim'):?><li>Irmã(o)</li><?php endif;?>
        <?php if($model[233] == 'Sim'):?><li>Madrasta</li><?php endif;?>
        <?php if($model[234] == 'Sim'):?><li>Padrasto</li><?php endif;?>
        <?php if($model[235] == 'Sim'):?><li>Avô/Avó</li><?php endif;?>
        <?php if($model[236]):?><li><?=$model[236]?></li><?php endif;?>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan=3><strong>Sua familia possui:</strong><br>
      <ul>
        <?php if($model[237] == 'Sim'):?><li>Bens Imóveis <u><?=$model[238]?></u></li><?php endif;?>
        <?php if($model[239] == 'Sim'):?><li>Bens Móveis <u><?=$model[240]?></u></li><?php endif;?>
        <?php if($model[241] == 'Sim'):?><li>Participações Societárias<u><?=$model[242]?></u></li><?php endif;?>
        <?php if($model[243] == 'Sim'):?><li>Aplicações e Investimentos<u><?=$model[244]?></u></li><?php endif;?>
        <?php if($model[245] == 'Sim'):?><li>Contas Bancárias (corrente, poupança, investimento)<u><?=$model[246]?></u></li><?php endif;?>
        <?php if($model[247]):?><li><?=$model[247]?> <u><?=$model[248]?></u></li><?php endif;?>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan=3><strong>Sua família é atendida por um ou mais programas de transferência de renda? </strong> <span> <?=$model[249]?> </span></td>
  </tr>
  <tr>
    <td colspan=3><strong>Qual(is) programas de transferência de renda?</strong><br>
      <ul>
      <?php if($model[250] == 'Sim'):?><li>Auxílio Brasil</li><?php endif;?>
      <?php if($model[251] == 'Sim'):?><li>Auxílio Pesca</li><?php endif;?>
      <?php if($model[252] == 'Sim'):?><li>Benefício de Prestação Continuada (BPC)</li><?php endif;?>
      <?php if($model[253] == 'Sim'):?><li>Bolsa Estiagem</li><?php endif;?>
      <?php if($model[254] == 'Sim'):?><li>Defeso</li><?php endif;?>
      <?php if($model[255] == 'Sim'):?><li>Garantia Safra</li><?php endif;?>
      <?php if($model[256] == 'Sim'):?><li>Programa Bolsa Família (PBF)</li><?php endif;?>
      <?php if($model[257] == 'Sim'):?><li>Programa de Erradicação do Trabalho Infantil (PETI)</li><?php endif;?>
      <?php if($model[258] == 'Sim'):?><li>Programa Primeiros Passos</li><?php endif;?>
      <?php if($model[259]):?><li><?=$model[259]?></li><?php endif;?>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan=2><strong>Qual a distância entre a moradia da sua família e a Universidade? </strong> <span> <?=$model[260]?> </span></td>
    <td><strong>Sua família mora em um imóvel: </strong> <span> <?=$model[261]?> </span></td>
  </tr>
  <tr>
    <td colspan=3><strong>Na casa da sua família tem:</strong><br>
      <ul>
        <li>Energia elétrica: <u><?=$model[262]?></u> </li>
        <li>
          Esgotamento sanitário: <u><?=$model[263]?></u>
          <?php if($model[263] == 'NÃO'):?><ul><li><?=$model[268] ? $model[268] : $model[267]?></li></ul><?php endif;?>
        </li>
        <li>Sanitário: <u><?=$model[264]?></u></li>
        <li>
          Coleta de Lixo: <u><?=$model[265]?></u>
          <?php if($model[265] == 'NÃO'):?><ul><li><?=$model[270] ? $model[270] : $model[269]?></li></ul><?php endif;?>
        </li>
        <li>Dormitóritos utilizados por mais de duas pessoas: <u><?=$model[266]?></u></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan=3><strong>Como ocorre o abastecimento de água? </strong> <span> <?=$model[271] == 'Outros' ? $model[272] : $model[271]?> </span></td>
  </tr>
  <tr>
    <td colspan=3 style="text-align:center"><strong>Informe os valores das despesas da/o candidato e da família no mês anterior ao Edital </strong></td>
  </tr>
  <tr>
    <th width="50%"></th>
    <th width="25%">ESTUDANTE</th>
    <th width="25%">FAMÍLIA</th>
  </tr>
  <tr>
    <td>ÁGUA</td>
    <td><?=converterMoeda($model[273])?></td>
    <td><?=converterMoeda($model[274])?></td>
  </tr>
  <tr>
    <td>ALIMENTAÇÃO</td>
    <td><?=converterMoeda($model[275])?></td>
    <td><?=converterMoeda($model[276])?></td>
  </tr>
  <tr>
    <td>EDUCAÇÃO</td>
    <td><?=converterMoeda($model[277])?></td>
    <td><?=converterMoeda($model[278])?></td>
  </tr>
  <tr>
    <td>ENERGIA ELÉTRICA</td>
    <td><?=converterMoeda($model[279])?></td>
    <td><?=converterMoeda($model[280])?></td>
  </tr>
  <tr>
    <td>INTERNET</td>
    <td><?=converterMoeda($model[281])?></td>
    <td><?=converterMoeda($model[282])?></td>
  </tr>
  <tr>
    <td>TELEFONE FIXO</td>
    <td><?=converterMoeda($model[283])?></td>
    <td><?=converterMoeda($model[284])?></td>
  </tr>
  <tr>
    <td>TELEFONE MÓVEL</td>
    <td><?=converterMoeda($model[285])?></td>
    <td><?=converterMoeda($model[286])?></td>
  </tr>
  <tr>
    <td>ALUGUEL/FINANCIAMENTO</td>
    <td><?=converterMoeda($model[287])?></td>
    <td><?=converterMoeda($model[288])?></td>
  </tr>
  <tr>
    <td>IPTU</td>
    <td><?=converterMoeda($model[289])?></td>
    <td><?=converterMoeda($model[290])?></td>
  </tr>
  <tr>
    <td>CONDOMÍNIO</td>
    <td><?=converterMoeda($model[291])?></td>
    <td><?=converterMoeda($model[292])?></td>
  </tr>
  <tr>
    <td>MÉDICOS/DENTISTAS/PLANOS DE SAÚDE</td>
    <td><?=converterMoeda($model[293])?></td>
    <td><?=converterMoeda($model[294])?></td>
  </tr>
  <tr>
    <td>REMÉDIOS DE USO CONTÍNUO</td>
    <td><?=converterMoeda($model[295])?></td>
    <td><?=converterMoeda($model[296])?></td>
  </tr>
  <tr>
    <td>TRANSPORTE</td>
    <td><?=converterMoeda($model[297])?></td>
    <td><?=converterMoeda($model[298])?></td>
  </tr>
</table>

<table style="margin:20px 0" class="table table-bordered" width="100%">
<tr><td colspan=2 style="background: #e1dfdf;text-align: center;font-weight: bold;color:black;">MAPEAMENTO - COVID-19</td></tr>
  <tr>
    <td colspan=2><strong>Pertence a algum grupo de risco de complicações da COVID-19? </strong><br>
      <ul>
        <?php if($model[301] == 'Sim'): ?><li>Não</li><?php endif;?>
        <?php if($model[302] == 'Sim'): ?><li>Idade igual ou superior a 60 anos </li><?php endif;?>
        <?php if($model[303] == 'Sim'): ?><li>Tabagismo</li><?php endif;?>
        <?php if($model[304] == 'Sim'): ?><li>Obesidade</li><?php endif;?>
        <?php if($model[305] == 'Sim'): ?><li>Miocardiopatias de diferentes etiologias (insuficiência cardíaca, miocardiopatia isquêmica etc.)</li><?php endif;?>
        <?php if($model[306] == 'Sim'): ?><li>Hipertensão arterial</li><?php endif;?>
        <?php if($model[307] == 'Sim'): ?><li>Doença cerebrovascular</li><?php endif;?>
        <?php if($model[308] == 'Sim'): ?><li>Pneumopatias graves ou descompensadas (asma moderada/grave, DPOC)</li><?php endif;?>
        <?php if($model[309] == 'Sim'): ?><li>Imunodepressão e imunossupressão</li><?php endif;?>
        <?php if($model[310] == 'Sim'): ?><li>Doenças renais crônicas em estágio avançado (graus 3, 4 e 5)</li><?php endif;?>
        <?php if($model[311] == 'Sim'): ?><li>Diabetes melito, conforme juízo clínico</li><?php endif;?>
        <?php if($model[312] == 'Sim'): ?><li>Doenças cromossômicas com estado de fragilidade imunológica</li><?php endif;?>
        <?php if($model[313] == 'Sim'): ?><li>Neoplasia maligna (exceto câncer não melanótico de pele)</li><?php endif;?>
        <?php if($model[314] == 'Sim'): ?><li>Cirrose hepática</li><?php endif;?>
        <?php if($model[315] == 'Sim'): ?><li>Anemia falciforme</li><?php endif;?>
        <?php if($model[316] == 'Sim'): ?><li>Talassemia</li><?php endif;?>
        <?php if($model[317] == 'Sim'): ?><li>Outras doenças hematológicas</li><?php endif;?>
        <?php if($model[318] == 'Sim'): ?><li>Gestação</li><?php endif;?>
        <?php if($model[319] == 'Sim'): ?><li>Outra condição atestada por médico/a: <?=$model[320]?></li><?php endif;?>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan=2><strong>Conforme §9º do Art 1 da Resolução do CONSUNI nº 07/2021, você poderá desempenhar suas atividades acadêmicas em regime de exercícios domiciliares regulamentado pela Resolução CAE 05/2018 em pelo menos um componente curricular, caso se enquadre nos casos listados no §8º do Art. 1. Esse é o seu caso? </strong> <span> <?=$model[321]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Especifique o motivo da demanda por exercícios domiciliares </strong><br>
      <ul>
        <?php if($model[322] == 'Sim'): ?><li>I - ter idade igual ou superior a sessenta anos </li><?php endif;?>
        <?php if($model[323] == 'Sim'): ?><li>II – tabagismo</li><?php endif;?>
        <?php if($model[324] == 'Sim'): ?><li>III – obesidade</li><?php endif;?>
        <?php if($model[325] == 'Sim'): ?><li>IV - miocardiopatias de diferentes etiologias (insuficiência cardíaca, miocardiopatia isquêmica etc.)</li><?php endif;?>
        <?php if($model[326] == 'Sim'): ?><li>V - hipertensão arterial</li><?php endif;?>
        <?php if($model[327] == 'Sim'): ?><li>VI - doença cerebrovascular</li><?php endif;?>
        <?php if($model[328] == 'Sim'): ?><li>VII - pneumopatias graves ou descompensadas (asma moderada/grave, DPOC)</li><?php endif;?>
        <?php if($model[329] == 'Sim'): ?><li>VIII - imunodepressão e imunossupressão</li><?php endif;?>
        <?php if($model[330] == 'Sim'): ?><li>IX - doenças renais crônicas em estágio avançado (graus 3, 4 e 5)</li><?php endif;?>
        <?php if($model[331] == 'Sim'): ?><li>X - diabetes melito, conforme juízo clínico</li><?php endif;?>
        <?php if($model[332] == 'Sim'): ?><li>XI - doenças cromossômicas com estado de fragilidade imunológica</li><?php endif;?>
        <?php if($model[333] == 'Sim'): ?><li>XII - neoplasia maligna (exceto câncer não melanótico de pele)</li><?php endif;?>
        <?php if($model[334] == 'Sim'): ?><li>XIII- cirrose hepática</li><?php endif;?>
        <?php if($model[335] == 'Sim'): ?><li>XIV - doenças hematológicas (incluindo anemia falciforme e talassemia)</li><?php endif;?>
        <?php if($model[336] == 'Sim'): ?><li>XV - estar em período de gestação ou lactação</li><?php endif;?>
        <?php if($model[337] == 'Sim'): ?><li>XVI - ter alguma condição que impeça a vacinação contra a COVID-19 por contraindicação médica</li><?php endif;?>
        <?php if($model[338] == 'Sim'): ?><li>XVII - servidores e empregados públicos na condição de pais, padrastos ou madrastas que possuam filhos ou que tenham a guarda de menores em idade escolar ou inferior, nos locais onde ainda estiverem mantidas a suspensão das aulas presenciais ou dos serviços de creche, que necessitem da assistência de um dos pais ou guardião, que não possua cônjuge, companheiro ou outro familiar adulto na residência apto a prestar assistência</li><?php endif;?>
        <?php if($model[339] == 'Sim'): ?><li>XVIII - estar encarregado de pessoa que necessite de atenção especial ou que com ela coabite, mesmo que não esteja com a infecção ou com suspeita de COVID-19</li><?php endif;?>
        <?php if($model[340] == 'Sim'): ?><li>XIX - estar em condição clínica ou psicossocial que não esteja prevista nos casos acima, mas que seja validada pelo Comitê de Assessoramento do Coronavírus como impeditiva do trabalho presencial</li><?php endif;?>
      </ul>
    </td>
  </tr>
  <tr>
    <td colspan=2><strong>Apresentou sintomas (típicos ou raros) de COVID-19? </strong> <span> <?=$model[341]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Há quanto tempo fez sua última testagem para COVID-19? </strong> <span> <?=$model[342]?> </span></td>
  </tr>
  <tr>
    <td colspan=2><strong>Qual o resultado da testagem? </strong> <span> <?=$model[343]?></span></td>
  </tr>
  <tr>
    <td colspan=2>
      <strong>Você se vacinou contra a COVID-19? </strong> <span> <?=$model[344]?> </span> <br>
      <?php if($model[345]):?><ul><li><?=$model[345]?></li></ul><?php endif?>
  </td>
  </tr>
</table>

</div>
</div>
</div>
<p><strong>Formulário enviado em: <?=Yii::$app->formatter->asDatetime($model[1])?>.</strong></p>
<?php SiscatPdfWidget::end();?>