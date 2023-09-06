<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhAfastamento */

$this->title = Yii::t('app', 'Occurrence');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisrh')), 'url' => ['/'.strtolower(Yii::t('app', 'sisrh'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Occurrences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-afastamento-view">

    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => 'Ocorrência'],
        'enableEditMode' => false,
        'attributes' => [
            [
                'columns' => [
                    ['attribute' => 'id_pessoa','value' => $model->pessoa->nome, 'valueColOptions'=>['style'=>'width:30%'],],
                    ['attribute' => 'id_ocorrencia', 'value' => $model->ocorrencia->justificativa, 'valueColOptions'=>['style'=>'width:30%'],],
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'inicio','format' => 'date', 'valueColOptions'=>['style'=>'width:30%'],],
                    ['attribute' => 'termino','format' => 'date', 'valueColOptions'=>['style'=>'width:30%'],],
                ]
            ],
            ['attribute' =>'observacao', 'options'=>['rows'=>4]],
        ],
    ]) ?>

</div>
