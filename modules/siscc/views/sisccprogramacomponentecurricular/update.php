<?php

use yii\helpers\Html;
use app\assets\SisccAsset;
SisccAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricular */

$this->title = $this->title = 'Atualizar Programa de Componente Curricular';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Programa de Componente Curricular', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_programa_componente_curricular, 'url' => ['view', 'id' => $model->id_programa_componente_curricular]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siscc-programa-componente-curricular-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
