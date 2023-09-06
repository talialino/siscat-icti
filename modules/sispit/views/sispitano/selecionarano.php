<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use app\modules\sispit\models\SispitAno;
use app\assets\SispitAsset;
SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitAnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Selecionar Ano';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-ano-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'itemOptions' => ['class' => 'item col-sm-2'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model->string), ['select', 'id' => $model->id_ano]);
        },
    ]) ?>
    </div>
</div>
