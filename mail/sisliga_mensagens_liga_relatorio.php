<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;

$brasaoUfba = Url::home(true). "images/brasao_ufba.jpg";

$mensagens = [
    1 => "foi submetido para avaliação.",
    2 => "foi encaminhado para você apreciar e emitir parecer.",
    3 => "foi aprovado pelo parecerista.",
    4 => "não foi aprovado pelo parecerista pois necessita de correções.",
    5 => "já sofreu as modificações recomendadas.",
    6 => "foi aprovado pela comissão.",
    7 => "foi homologado pela Congregação.",
    8 => "não foi homologado.",
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
    <p><?='A seguinte liga acadêmica ou relatório de liga '.$mensagens[$documento->situacao]?></p>
    <?= DetailView::widget([
        'model' => $documento,
        'panel' => ['type' => 'default', 'heading' => 'Liga Acadêmica/Relatório', 'before' => false,
        'after' => false],
        'enableEditMode' => false,
        'attributes' => [
            'nome:ntext',
            ['attribute' => 'id_pessoa', 'value' => $documento->pessoa->nome],
        ]
    ]);?>
    <?= Html::a('Para acessar o sistema, clique aqui!', Url::home('http')) ?>
    <hr />   
    <span style="font-size:10px; text-align:center; color:grey">
        IMS/CAT-UFBA - Rua Rio de Contas, 58 - Quadra 17 - Lote 58 - Bairro Candeias <br />
        Vitória da Conquista - BA - CEP: 45.029-094/ Fone: (77)3429 2709. <br />
        E-mail:catims@ufba.br
    </span>
</div>