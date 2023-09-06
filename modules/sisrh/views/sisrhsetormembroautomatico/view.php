<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetorMembroAutomatico */

$this->title = "Visualizar Membro Automático";
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisrh')), 'url' => ['/'.strtolower(Yii::t('app', 'sisrh'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Membros Automáticos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-setor-membro-automatico-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id_membro_automatico], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id_membro_automatico], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => $this->title],
        'enableEditMode' => false,
        'attributes' => [
            [
                'columns' => [
                    ['attribute' => 'id_setor_origem','value' => $model->setorOrigem ? $model->setorOrigem->nome : '-','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'funcaoOrigem','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
            [
                'columns' => [
                    ['attribute' => 'id_setor_destino','value' => $model->setorDestino ? $model->setorDestino->nome : '-','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                    ['attribute' => 'funcaoDestino','valueColOptions'=>['style'=>'width:38%'], 'labelColOptions' => ['style'=>'width:12%']],
                ]
            ],
        ],
    ]) ?>

</div>
