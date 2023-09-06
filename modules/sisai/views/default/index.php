<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

$this->title = 'SISAI - Sistema de Avaliação Institucional';
?>
<div class="sisai-default-index">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <div class="box box-info">
        <?php if($periodoAvaliacao):?>
            <div class="mensagem-inicial"><?="Bem vind@ ao SISAI - {$periodoAvaliacao->semestre->string}"?></div>
            <div style="padding:10px"><?=Html::a('Seguir para Avaliação', ['avaliacao'], ['class' => 'btn btn-success btn-lg']) ?></div>
        <?php else:?>
            <div class="mensagem-inicial">Bem vind@ ao SISAI. No momento, não temos nenhum período de avaliação ativo.</div>
        <?php endif;?>
    </div>
</div>
