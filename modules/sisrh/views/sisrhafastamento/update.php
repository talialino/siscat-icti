<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhAfastamento */

$this->title = Yii::t('app', 'Update Occurrence');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisrh')), 'url' => ['/'.strtolower(Yii::t('app', 'sisrh'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Occurrences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Occurrence'), 'url' => ['view', 'id' => $model->id_afastamento]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sisrh-afastamento-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
