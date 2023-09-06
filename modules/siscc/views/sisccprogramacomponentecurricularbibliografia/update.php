<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografia */

$this->title = $this->title = 'Update Siscc Programa Componente Curricular Bibliografia';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Siscc Programa Componente Curricular Bibliografias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_programa_componente_curricular, 'url' => ['view', 'id_programa_componente_curricular' => $model->id_programa_componente_curricular, 'id_bibliografia' => $model->id_bibliografia]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siscc-programa-componente-curricular-bibliografia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
