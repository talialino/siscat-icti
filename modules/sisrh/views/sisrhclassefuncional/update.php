<?php

use yii\helpers\Html;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhClasseFuncional */

$this->title = Yii::t('app','Update Functional Class');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Functional Classes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->denominacao, 'url' => ['view', 'id' => $model->id_classe_funcional]];
$this->params['breadcrumbs'][] = Yii::t('app','Update');
?>
<div class="sisrh-classe-funcional-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
