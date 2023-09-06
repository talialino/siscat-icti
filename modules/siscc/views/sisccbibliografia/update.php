<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccBibliografia */

$this->title = $this->title = 'Update Siscc Bibliografia';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Siscc Bibliografias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_bibliografia, 'url' => ['view', 'id' => $model->id_bibliografia]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siscc-bibliografia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
