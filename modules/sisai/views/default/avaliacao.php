<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

$this->title = 'SISAI - Sistema de Avaliação Institucional';
?>
<div class="sisai-default-index">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <div style="background: white;  padding: 20px;  border-top: 5px solid #ccc;">
    <h3>Escolha qual avaliação vai realizar:</h3>
    <?php if($contador == 1 || $contador == 3 || $contador == 5 || $contador == 7):?>
        <?=Html::a('Discente',['sisaiavaliacao/discente'], ['class'=>'btn btn-sisai btn-lg'])?>
    <?php endif;?>

    <?php if($contador == 2 || $contador == 3 || $contador == 6 || $contador == 7):?>
        <?=Html::a('Docente',['sisaiavaliacao/docente'], ['class'=>'btn btn-sisai btn-lg'])?>
    <?php endif;?>

    <?php if($contador == 4 || $contador == 5 || $contador == 6 || $contador == 7):?>
        <?=Html::a('Técnico',['sisaiavaliacao/tecnico'], ['class'=>'btn btn-sisai btn-lg'])?>
    <?php endif;?>
    </div>
</div>