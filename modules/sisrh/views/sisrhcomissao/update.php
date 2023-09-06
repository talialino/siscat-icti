<?php

use yii\helpers\Html;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetor */

$this->title = 'Atualizar Comissão';
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => 'Comissões', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id_comissao]];
$this->params['breadcrumbs'][] = Yii::t('app','Update');
?>
<div class="sisrh-comissao-update">

<h1 style="margin-bottom:0;padding-bottom:20px" class="cabecalho"><?= Html::encode($this->title) ?> <br /> <span style="font-size:16px"> <?= Html::encode($model->nome)?></span></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
