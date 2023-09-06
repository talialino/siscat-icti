<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;

SisaiAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiColegiadoSemestreAtuv */

$this->title = Yii::t('app', 'Criar Colegiado Semestre Atuv');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sisai Colegiado Semestre Atuvs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-colegiado-semestre-atuv-create">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
