<?php

use yii\helpers\Html;
//use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;
use app\assets\SispitAsset;

SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPlanoRelatorio */

$this->title = $pit_rit ? "Relatório Individual de Trabalho".($model->ehRitParcial() ? ' (Parcial)' : '') : "Plano Individual de Trabalho";
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;

$pendencias = $model->isEditable() ? !$model->validarCargaHoraria($pit_rit) : false;
?>
<div class="sispit-plano-relatorio-visualizar">

    <h1 class="cabecalho"><span><?= Html::encode($this->title) ?></span></h1>
    <?php if($pendencias || $model->ehRitParcial()):?>
        <?php if($model->exibirBotaoSubmeterRitParcial()):?>
            <?php ActiveForm::begin(['action' => ['sispitplanorelatorio/submeterritparcial', 'id' => $model->id_plano_relatorio]]);?>
            <div class ="form-group">
                <?= Html::submitButton('Submeter RIT parcial', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end();?>
        <?php elseif($pendencias):?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Pendências</h3>
            </div>
            <div class="form-group has-error">
                <?php foreach($model->getErrors('id_plano_relatorio') as $erro):?>
                    <div class="msgErrorSispit"><?= $erro?></div>
                <?php endforeach;?>
            </div>
        </div>
        <?php endif;?>
    <?php elseif($model->isEditable() && !$model->ehRitParcial()):?>
        <?php ActiveForm::begin(['action' => ['sispitplanorelatorio/submeter', 'id' => $model->id_plano_relatorio]]);?>
            <div class ="form-group">
                <?= Html::submitButton('Submeter', ['class' => 'btn btn-success']) ?>
            </div>
    <?php else:?>
        <div style="margin-bottom: 35px; float: right;">
            <div class="msgInfoSispit"><strong>SITUAÇÃO: </strong><?=$model->situacao?></div>
        </div>
        <div class="clearfix"></div>
    <?php endif;?>

    <?= $this->render('_view.php', $model->planoRelatorioToArray($pit_rit));?>
    
    <?php if(!$pendencias && $model->isEditable() && !$model->ehRitParcial()):?>
            <div class ="form-group">
                <?= Html::submitButton('Submeter', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end();?>
    <?php
        elseif($model->status % 10 == 1):
            echo Html::a('Editar Novamente', ['editarnovamente', 'id' => $model->id_plano_relatorio], ['class' => 'btn btn-primary']);
        endif;
    ?>

<?php
    if($model->status == 9 || $model->status == 19)
        echo Html::a('Salvar PDF', ['pdf', 'id' => $model->id_plano_relatorio], ['class' => 'btn btn-primary btn-print']);
?>
</div>
