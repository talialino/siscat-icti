<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiAvaliacao */

$this->title = $this->title = Yii::t('app', 'Update Sisai Avaliacao');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sisai Avaliacaos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_avaliacao, 'url' => ['view', 'id' => $model->id_avaliacao]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sisai-avaliacao-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
