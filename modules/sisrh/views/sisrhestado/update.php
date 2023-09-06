<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhEstado */

$this->title = Yii::t('app','Update State');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id_estado]];
$this->params['breadcrumbs'][] = Yii::t('app','Update');
?>
<div class="sisrh-estado-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
