<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetor */

$this->title = Yii::t('app','Update Sector');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Sectors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id_setor]];
$this->params['breadcrumbs'][] = Yii::t('app','Update');
?>
<div class="sisrh-setor-update">

<h1 style="margin-bottom:0;padding-bottom:10px" class="cabecalho"><?= Html::encode($this->title) ?> <br /> <span style="font-size:18px"><?= Html::encode($model->nome)?></span></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
