<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhOcorrencia */

$this->title = 'Adicionar Tipo de Ocorrência';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisrh'), 'url' => ['/'.strtolower('sisrh')]];
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Ocorrência', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-ocorrencia-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
