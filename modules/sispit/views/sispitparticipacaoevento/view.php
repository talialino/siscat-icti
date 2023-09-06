<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitParticipacaoEvento */

$this->title = $model->id_participacao_evento;
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = ['label' => 'Sispit Participacao Eventos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-participacao-evento-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_participacao_evento], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_participacao_evento], [
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
            'id_participacao_evento',
            'id_plano_relatorio',
            'nome:ntext',
            'semestre',
            'tipo_evento',
            'tipo_participacao_evento',
            'local',
            'data_inicio',
            'data_fim',
            'pit_rit',
        ],
    ]) ?>

</div>
