<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
use app\modules\sisai\models\QuestionarioForm;

SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiQuestionario */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisai'), 'url' => ['/'.strtolower('sisai')]];
$this->params['breadcrumbs'][] = ['label' => 'Questionários', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sisai-questionario-view">

    <h1 class="cabecalho" style="padding: 20px 20px 0; margin-bottom:2px"><?= Html::encode($this->title) ?></h1>
    <h2 class="questionario-sub-titulo"><?= Html::encode($model->subtitulo) ?></h2>
    <?php
        $tamanhoBarraEtapas = round(($model->avaliacao->etapas[0]/$model->avaliacao->etapas[1]*100),0);
    ?>
    <div class="progress">
        <div style="width:<?=$tamanhoBarraEtapas?>%" class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div> 
    <div><span class="pull-right questionario-etapas"><?="Etapa {$model->avaliacao->etapas[0]} de {$model->avaliacao->etapas[1]}"?></span></div>
    <div class="clearfix"></div>
    <?php if($model->errosCarregamentoQuestionario):?>
    <div class="bg-danger erro-questionario">
        <h4 class='text-danger'>Não foi possível salvar as respostas:</h4>
        <ul>
            <?php foreach($model->errosCarregamentoQuestionario as $erro):?>
                <li class="text-danger"> <?=$erro?></li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php endif;?>
    <?= $this->render('/sisaiquestionario/_questionario', ['model' => $model])?>
</div>