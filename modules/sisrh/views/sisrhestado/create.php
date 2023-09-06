<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhEstado */

$this->title = Yii::t('app','Create State');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-estado-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
