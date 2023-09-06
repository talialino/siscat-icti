<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\assets\SiscoaeAsset;
SiscoaeAsset::register($this);


/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthItemSisrhSetorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Formulário Socioeconômico';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="formulario-socioeconomico-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <div style="padding:20px;margin: 0 auto 20px; max-width: 700px;" class="box box-primary">
        <?php $form = ActiveForm::begin(['id' => 'contact-form','options' => ['class' => 'form-inline']]); ?>
            <div class="form-group">
                <?= $form->field($model, 'nome')->label('Arquivo com dados para analisar')->dropDownList($model->listaArquivos(),['prompt' => 'Escolha o arquivo']) ?>
            </div>
            <div class="form-group" style=" vertical-align: top;">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
        <p>
        Caso o arquivo não esteja na lista, clique <?= Html::a(Yii::t('app', 'AQUI'), ['uploadcsv'], []) ?>.
        </p>
    </div>
    
    <?php if($model->nome) {
        echo '<div style="padding:20px;margin: 0 auto 20px; max-width: 700px;" class="lista-formularios box box-primary" >
        <ol>';
        $total = $model->lerRegistrosCsv();
        for($i = 1; $i<= count($total); $i++)
        {
                echo '<li>';
                echo Html::a('Formulário ID-'.($total[$i]),['formulariosocioeconomico','pagina' => ($i)], ['download' => '']);
                echo '</li>';
            }
        }
        echo '</ol></div>'
    ?>
</div>
