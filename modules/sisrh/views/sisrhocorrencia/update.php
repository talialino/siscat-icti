<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhOcorrencia */

$this->title = $this->title = 'Atualizar Tipo de Ocorrência';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisrh'), 'url' => ['/'.strtolower('sisrh')]];
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Ocorrências', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Tipo de Ocorrência', 'url' => ['view', 'id' => $model->id_ocorrencia]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sisrh-ocorrencia-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
