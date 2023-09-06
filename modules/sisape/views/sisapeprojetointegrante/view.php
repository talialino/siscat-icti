<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeProjetoIntegrante */

$this->title = $model->id_projeto_integrante;
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sisape Projeto Integrantes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-projeto-integrante-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id_projeto_integrante], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id_projeto_integrante], [
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
            'id_projeto_integrante',
            'id_projeto',
            'id_integrante_externo',
            'id_pessoa',
            'funcao',
            'id_aluno',
            'carga_horaria',
            'vinculo',
        ],
    ]) ?>

</div>
