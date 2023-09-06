<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\modules\sisrh\models\SisrhSetor;
use yii\bootstrap\Modal;
use yii\helpers\Html;

Modal::begin([
    'header' => "<h3>$this->title</h3>",
    'id' => 'modal',
    'size' =>'modal-md',
    'options' => ['tabindex' => false,],
]);
echo "<div id='modalContent'></div>";
Modal::end();

?>


    <?= $form->field($model, 'situacao')->label(false)->hiddenInput() ?>

    <?= $form->field($model, 'nome',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'id_setor',['options' => ['class' => 'col-md-6']])->dropDownList(ArrayHelper::map(SisrhSetor::find()->where(['eh_colegiado' => 1])->all(), 'id_setor','nome'), ['prompt' => '']) ?>

    <?= $form->field($model, 'area_conhecimento',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'resumo',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 10]) ?>

    <?= $form->field($model, 'local_atuacao',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 2]) ?>

    <?php
        if($model->isNewRecord)
            echo $form->field($model, 'solicitacao',['options' => ['class' => 'col-md-4']])->fileInput();
        else
            echo "<div class='col-md-4 field-sisligaligaacademica-solicitacao'>
                <label class='control-label' for='sisligaligaacademica-solicitacao'>Ofício de Solicitação de Cadastro</label>
                <div class='sisligaligaacademica-solicitacao-visualizar'>
                    <a href='".Url::to(['visualizararquivo','id' => $model->id_liga_academica, 'tipoArquivo' => 1])."' target='_blank' class='btn btn-primary' role='button'>Visualizar arquivo</a>
                    ".Html::button('Alterar',
                                [
                                'value' =>Url::to(['alterararquivo','id' => $model->id_liga_academica, 'tipoArquivo' => 1]),
                                'title' => 'Alterar arquivo da solicitação',
                                'class' => 'btn btn-warning modalButton'
                    ])."
                </div>
                <div class='help-block'></div>
            </div>";
    ?>

    <?php
        if($model->isNewRecord)
            echo $form->field($model, 'regimento',['options' => ['class' => 'col-md-4']])->fileInput();
        else
            echo "<div class='col-md-4 field-sisligaligaacademica-regimento'>
                <label class='control-label' for='sisligaligaacademica-regimento'>Regimento da Liga Acadêmica</label>
                <div class='sisligaligaacademica-regimento-visualizar'>
                    <a href='".Url::to(['visualizararquivo','id' => $model->id_liga_academica, 'tipoArquivo' => 2])."' target='_blank' class='btn btn-primary' role='button'>Visualizar arquivo</a>
                    ".Html::button('Alterar',
                                [
                                'value' =>Url::to(['alterararquivo','id' => $model->id_liga_academica, 'tipoArquivo' => 2]),
                                'title' => 'Alterar arquivo do regimento',
                                'class' => 'btn btn-warning modalButton'
                    ])."
                </div>
                <div class='help-block'></div>
            </div>";
    ?>
    
    <div class='form-group col-md-12'>
    <hr style='border-top: 1px solid #c7c7c7;'>
        <button type='submit' class='pull-right btn btn-success btn-lg'>Salvar</button>
    </div>

    <div class="clearfix"></div>