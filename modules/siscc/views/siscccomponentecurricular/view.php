<?php

use app\assets\SisccAsset;
SisccAsset::register($this);

?>
<div class="flex-row row viewComponenteCurricular">
    <div class="col-sm-9"><strong>Nome: </strong><?=$model->nome?></div>
    <div class="col-sm-3"><strong>Código: </strong><?=$model->codigo_componente?></div>
    <div class="col-sm-3"><strong>CH Teórica: </strong><?=$model->ch_teorica?></div>
    <div class="col-sm-3"><strong>CH Prática: </strong><?=$model->ch_pratica?></div>
    <div class="col-sm-3"><strong>CH Estágio: </strong><?=$model->ch_estagio?></div>
    <div class="col-sm-3"><strong>CH Extensão: </strong><?=$model->ch_extensao?></div>
    <div class="col-sm-3"><strong>Módulo Teoria: </strong><?=$model->modulo_teoria?></div>
    <div class="col-sm-3"><strong>Módulo Prática: </strong><?=$model->modulo_pratica?></div>
    <div class="col-sm-3"><strong>Módulo Estágio: </strong><?=$model->modulo_estagio?></div>
    <div class="col-sm-3"><strong>Módulo Extensão: </strong><?=$model->modulo_extensao?></div>
    <div class="col-sm-6"><strong>Modalidade/Submodalidade: </strong><?=$model->modalidadeSubmodalidade?></div>
    <div class="col-sm-6"><strong>Pré-requisito: </strong><?=$model->prerequisitos?></div>
    <div class="col-sm-12"><strong>Ementa: </strong><?=$model->ementa?></div>
</div>