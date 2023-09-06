<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccBibliografia */

$this->title = 'Create Siscc Bibliografia';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Siscc Bibliografias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-bibliografia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
