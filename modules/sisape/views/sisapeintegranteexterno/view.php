<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeIntegranteExterno */

$this->title = $model->id_integrante_externo;
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sisape Integrante Externos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-integrante-externo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id_integrante_externo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id_integrante_externo], [
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
            'id_integrante_externo',
            'nome',
            'email:email',
            'telefone',
        ],
    ]) ?>

</div>
