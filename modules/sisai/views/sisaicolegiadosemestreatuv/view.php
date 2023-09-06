<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiColegiadoSemestreAtuv */

$this->title = $model->id_colegiado_semestre_atuv;
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sisai Colegiado Semestre Atuvs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-colegiado-semestre-atuv-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id_colegiado_semestre_atuv], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id_colegiado_semestre_atuv], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => $this->title],
        'enableEditMode' => false,
        'attributes' => [
            'id_colegiado_semestre_atuv',
            'colegiados_liberados',
            'id_semestre',
        ],
    ]) ?>

</div>
