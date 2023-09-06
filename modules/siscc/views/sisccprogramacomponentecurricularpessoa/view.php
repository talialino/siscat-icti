<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularPessoa */

$this->title = $model->id_programa_componente_curricular;
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Siscc Programa Componente Curricular Pessoas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-programa-componente-curricular-pessoa-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_programa_componente_curricular' => $model->id_programa_componente_curricular, 'id_pessoa' => $model->id_pessoa], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_programa_componente_curricular' => $model->id_programa_componente_curricular, 'id_pessoa' => $model->id_pessoa], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => $this->title],
        'enableEditMode' => false,
        'attributes' => [
            'id_programa_componente_curricular',
            'id_pessoa',
        ],
    ]) ?>

</div>
