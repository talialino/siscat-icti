<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhCargo */

$this->title =  Yii::t('app','Office');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Offices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-cargo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app','Update'), ['update', 'id' => $model->id_cargo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','Delete'), ['delete', 'id' => $model->id_cargo], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app','Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => 'Cargo'],
        'enableEditMode' => false,
        'attributes' => [
            [
                'columns' => [
                    ['attribute' => 'descricao', 'valueColOptions' => ['style' => 'witdh: 30%']],
                    ['attribute' => 'id_categoria', 'valueColOptions' => ['style' => 'witdh: 30%'], 'value' => $model->categoria->nome],
                ]
                ],
            ['attribute' => 'atribuicoes', 'format' => 'raw', 'labelColOptions' => ['style' => 'vertical-align: top; width: 20%; text-align: right']],
        ],
    ]) ?>

</div>
