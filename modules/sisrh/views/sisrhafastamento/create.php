<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhAfastamento */

$this->title = Yii::t('app', 'Add Occurrence');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisrh')), 'url' => ['/'.strtolower(Yii::t('app', 'sisrh'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Occurrences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-afastamento-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
