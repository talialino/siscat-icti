<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitOrientacaoAcademica */
use app\assets\SispitAsset;
SispitAsset::register($this);

$this->title = $this->title = 'Atualizar Orientação Acadêmica';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = ['label' => 'Sispit Orientacao Academicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_orientacao_academica, 'url' => ['view', 'id' => $model->id_orientacao_academica]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sispit-orientacao-academica-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
