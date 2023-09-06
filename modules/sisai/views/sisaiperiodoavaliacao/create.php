<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;

SisaiAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiPeriodoAvaliacao */

$this->title = 'Criar Período de Avaliação';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisai'), 'url' => ['/'.strtolower('sisai')]];
$this->params['breadcrumbs'][] = ['label' => 'Períodos de Avaliação', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-periodo-avaliacao-create">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
