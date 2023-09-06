<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;

$brasaoUfba = Url::home(true). "images/brasao_ufba.jpg";

$mensagens = [
    1 => "foi submetido para avaliação.",
    2 => "foi encaminhado pelo colegiado para você avaliar e emitir parecer.",
    3 => "foi aprovado pelo parecerista.",
    4 => "não foi aprovado pelo parecerista do colegiado pois necessita de correções.",
    5 => "já sofreu as modificações recomendadas.",
    6 => "foi aprovado pelo colegiado.",
    7 => "foi encaminhado pela Coordenação Acadêmica para você avaliar e emitir parecer.",
    8 => "foi aprovado pelo parecerista.",
    9 => "não foi aprovado pelo parecerista da CAC pois necessita de correções.",
    10 => "já sofreu as modificações recomendadas.",
    11 => "foi homologado pela Coordenação Acadêmica.",
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
    <p><?='O seguinte programa de componente curricular '.$mensagens[$programa->situacao]?></p>
    <?= DetailView::widget([
        'model' => $programa,
        'panel' => ['type' => 'default', 'heading' => 'Programa de Componente Curricular', 'before' => false,
        'after' => false],
        'enableEditMode' => false,
        'attributes' => [
            'semestreString',            
            'componente',
            'colegiado',
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