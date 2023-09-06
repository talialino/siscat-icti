<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>

<div class="sisliga-alterar-arquivo-form">
    <?php
        Pjax::begin();
        $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'enctype' => 'multipart/form-data', 'data' => ['pjax' => true]],]);
    ?>
        <?= $form->field($model, $tipoArquivo == 1 ? 'solicitacao' : 'regimento')->fileInput(); ?>
        
        <button type='submit' class='pull-right btn btn-success btn-lg'>Salvar</button>
        
        <div class="clearfix"></div>

    <?php 
        ActiveForm::end();
        Pjax::end();
    ?>
</div>