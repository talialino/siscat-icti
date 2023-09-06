<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPlanoRelatorio */

$this->title = $this->title = 'Update Sispit Plano Relatorio';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = ['label' => 'Sispit Plano Relatorios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_plano_relatorio, 'url' => ['view', 'id' => $model->id_plano_relatorio]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sispit-plano-relatorio-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
