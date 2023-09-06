<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$gridColumns = [
    'siape',
    'nome',
    ['attribute' => 'situacao','value' => function($data){ return $data->situacao ? 'Ativo' : 'Inativo';}],
];

$this->title = Yii::t('app','Reports');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sisrh-afastamento-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php ?>

    <div class="table-responsive">
        <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Export All',
                    'class' => 'btn btn-default'
                ]
            ]) . "<hr>\n".
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'emptyText' => 'Não há resultados para essa pesquisa',
            ]); ?>
    </div>
</div>

