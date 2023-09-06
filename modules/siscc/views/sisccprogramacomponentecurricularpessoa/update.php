<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularPessoa */

$this->title = $this->title = 'Update Siscc Programa Componente Curricular Pessoa';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Siscc Programa Componente Curricular Pessoas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_programa_componente_curricular, 'url' => ['view', 'id_programa_componente_curricular' => $model->id_programa_componente_curricular, 'id_pessoa' => $model->id_pessoa]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siscc-programa-componente-curricular-pessoa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
