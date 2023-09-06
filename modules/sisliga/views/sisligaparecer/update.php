<?php

use yii\helpers\Html;
use app\assets\SisccAsset;

SisccAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaParecer */

$this->title = $this->title = 'Definir Parecer';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisliga'), 'url' => ['/'.strtolower('sisliga')]];
$this->params['breadcrumbs'][] = ['label' => 'Parecer', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Definir Parecer';
?>
<div class="sisliga-parecer-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render($model->id_liga_academica ? '/sisligaligaacademica/_view' : '/sisligarelatorio/_view', ['model' => $model->documento]); ?>

    <?= $this->render('_view', ['model' => $model->documento, 'parecer' => $model]) ?>

    <?= $this->render('_form', ['model' => $model,]) ?>

</div>
