<?php

use app\modules\sisrh\models\SisrhComissaoPessoa;
use yii\helpers\Html;
use yii\grid\GridView;

use app\modules\sisrh\models\SisrhSetorPessoa;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthItemSisrhSetorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permissões por Setor';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-sisrh-setor-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Criar Permissão'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'setorComissao.nome',
            'funcaoDescricao',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
