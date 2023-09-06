<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisai\models\SisaiAvaliacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Avaliações';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-avaliacao-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  [
            ['content' => 
                false,
            ],
        ],
        'columns' => [

            'id_avaliacao',
            'semestre.string',
            'id_aluno',
            'id_pessoa',
            'avaliador.nome',
            'tipoAvaliacao',
            'situacaoString',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
