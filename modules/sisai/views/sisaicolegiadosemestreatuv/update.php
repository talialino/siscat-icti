<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiColegiadoSemestreAtuv */

$this->title = $this->title = Yii::t('app', 'Update Sisai Colegiado Semestre Atuv');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sisai Colegiado Semestre Atuvs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_colegiado_semestre_atuv, 'url' => ['view', 'id' => $model->id_colegiado_semestre_atuv]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sisai-colegiado-semestre-atuv-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
