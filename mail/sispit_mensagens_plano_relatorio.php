<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\sisrh\models\SisrhPessoa;

$pessoa = SisrhPessoa::find()->where(['id_user' => $plano->user_id])->one();
$brasaoUfba = Url::home(true). "images/brasao_ufba.jpg";

$mensagens = [
    1 => "O PIT $plano->ano de $pessoa->nome foi submetido para avaliação.",
    2 => "O PIT $plano->ano de $pessoa->nome foi encaminhado pelo seu núcleo para você avaliar e emitir parecer, ou $pessoa->nome realizou as modificações que você recomendou.",
    3 => "O PIT $plano->ano de $pessoa->nome foi aprovado pelo parecerista.",
    4 => "O seu PIT $plano->ano não foi aprovado pelo parecerista do núcleo pois necessita de correções.",
    5 => "O PIT $plano->ano de $pessoa->nome foi aprovado pelo núcleo.",
    6 => "O PIT $plano->ano de $pessoa->nome foi encaminhado pela Coordenação Acadêmica para você avaliar e emitir parecer, ou $pessoa->nome realizou as modificações que você recomendou.",
    7 => "O PIT $plano->ano de $pessoa->nome foi aprovado pelo parecerista.",
    8 => "O seu PIT $plano->ano não foi aprovado pelo parecerista da CAC pois necessita de correções.",
    9 => "O seu PIT $plano->ano foi homologado pela Coordenação Acadêmica.",
    11 => "O RIT $plano->ano de $pessoa->nome foi submetido para avaliação.",
    12 => "O RIT $plano->ano de $pessoa->nome foi encaminhado pelo seu núcleo para você avaliar e emitir parecer, ou $pessoa->nome realizou as modificações que você recomendou.",
    13 => "O RIT $plano->ano de $pessoa->nome foi aprovado pelo parecerista.",
    14 => "O seu RIT $plano->ano não foi aprovado pelo parecerista do núcleo pois necessita de correções.",
    15 => "O RIT $plano->ano de $pessoa->nome foi aprovado pelo núcleo.",
    16 => "O RIT $plano->ano de $pessoa->nome foi encaminhado pela Coordenação Acadêmica para você avaliar e emitir parecer, ou $pessoa->nome realizou as modificações que você recomendou.",
    17 => "O RIT $plano->ano de $pessoa->nome foi aprovado pelo parecerista.",
    18 => "O seu RIT $plano->ano não foi aprovado pelo parecerista da CAC pois necessita de correções.",
    19 => "O seu RIT $plano->ano foi homologado pela Coordenação Acadêmica.",
];

?>
<div style="font-family: arial">
    <table style="border:0; padding-bottom:5px">
        <tr style="border:0;">
            <td style="border:0; width:70px"><img src="<?=$brasaoUfba?>"></td>
            <td style="border:0; font-size:16px; font-weight:bold">
            Universidade Federal da Bahia - Campus Anísio Teixeira<br>
            Instituto Multidisciplinar em Saúde<br>
            SISPIT - Sistema de PIT/RIT</td>
        </tr>
    </table> 
    <hr />   
    <p><?=$mensagens[$plano->status]?></p>
    <?= Html::a('Para acessar o sistema, clique aqui!', Url::home('http')) ?>
    <hr />   
    <span style="font-size:10px; text-align:center; color:grey">
        IMS/CAT-UFBA - Rua Rio de Contas, 58 - Quadra 17 - Lote 58 - Bairro Candeias <br />
        Vitória da Conquista - BA - CEP: 45.029-094/ Fone: (77)3429 2709. <br />
        E-mail:catims@ufba.br
    </span>
</div>