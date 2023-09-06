<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitEnsinoOrientacao */

$this->title = $model->id_ens_orientacao;
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = ['label' => 'Sispit Ensino Orientacaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-ensino-orientacao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_ens_orientacao], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_ens_orientacao], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_ens_orientacao',
            'id_plano_relatorio',
            'semestre',
            'id_aluno',
            'projeto:ntext',
            'tipo_orientacao',
            'carga_horaria',
            'pit_rit',
        ],
    ]) ?>

</div>
