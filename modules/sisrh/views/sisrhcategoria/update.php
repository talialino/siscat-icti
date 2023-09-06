<?php

use yii\helpers\Html;
use app\assets\SisrhAsset;

SisrhAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhCategoria */

$this->title = Yii::t('app','Update Category');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id_categoria]];
$this->params['breadcrumbs'][] = Yii::t('app','Update');
?>
<div class="sisrh-categoria-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
