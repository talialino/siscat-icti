<?php

use yii\helpers\Html;
use app\assets\SisccAsset;

SisccAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeParecer */

$this->title = $this->title = 'Definir Parecer';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisape'), 'url' => ['/'.strtolower('sisape')]];
$this->params['breadcrumbs'][] = ['label' => 'Parecer', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Definir Parecer';
?>
<div class="sisape-parecer-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?php //= $this->render('/sisapeprojeto/_view.php', ['model' => $model->getDocumento()]);?>

    <?= $this->render('_view', ['model' => $model->getDocumento(), 'parecer' => $model]) ?>

    <?= $this->render('_form', ['model' => $model,]) ?>

</div>
