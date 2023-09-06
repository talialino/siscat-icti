<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\sisrh\models\SisrhPessoa;

$pessoa = SisrhPessoa::find()->where(['id_user' => $plano->user_id])->one()->nome;
$brasaoUfba = Url::home(true). "images/brasao_ufba.jpg";

$documento = ($plano->isRitAvailable() ? 'RIT' : 'PIT')." $plano->ano";
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
    <p><?="O $documento de $pessoa que fora distribuido para você avaliar, foi redirecionado para outra pessoa."?></p>
    <?= Html::a('Para acessar o sistema, clique aqui!', Url::home('http')) ?>
    <hr />   
    <span style="font-size:10px; text-align:center; color:grey">
        IMS/CAT-UFBA - Rua Rio de Contas, 58 - Quadra 17 - Lote 58 - Bairro Candeias <br />
        Vitória da Conquista - BA - CEP: 45.029-094/ Fone: (77)3429 2709. <br />
        E-mail:catims@ufba.br
    </span>
</div>