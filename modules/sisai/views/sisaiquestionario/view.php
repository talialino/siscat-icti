<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
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

    <h1 class="cabecalho"><span style="font-weight:bold">PRÉVIA - </span><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_questionario', ['model' => new QuestionarioForm($model->id_questionario)])?>
</div>
