<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\SispitAsset;

SispitAsset::register($this);

$this->title = 'Informações';
?>
<div class="sispit-default-index">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <?php if($situacaoplano):?>
        <div class="sispit-mensagem-pessoal">
            <strong>Situação do seu PIT/RIT:</strong>
            <?=$situacaoplano?>
        </div>
    <?php endif; ?>
    <div class="clearfix"></div>
    <?php if($plano):?>
            <?php if($plano->status == 4 || $plano->status == 8 || $plano->status == 14 || $plano->status == 18):?>
                <?= $this->render('_comentarioparecer',['plano' => $plano])?>
            <?php endif;?>
    <?php endif;?>
</div>
