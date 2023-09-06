<?php
/* @var $this yii\web\View/ */

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;
use app\modules\sisrh\models\SisrhCargo;
use app\modules\sisrh\models\SisrhCategoria;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

$gridColumns = [
    ['attribute' => 'siape', 'width' => '5%', 'label' => 'Matrícula'],
    [
        'attribute' => 'nome',
        'value' => function($model){ 
            return $model->temOcorrenciaVigente() ? "<span class='ocorrenciaVigente'>$model->nome</span>" : $model->nome;
        },
        'format' => 'raw',
        'width' => '18%'
    ],
    [
        'attribute' => 'cargo.descricao',
        'value' => function($model) {
            return ucwords(mb_strtolower($model->cargo->descricao));
        },
         'filterType' => GridView::FILTER_SELECT2,
         'filter' => ArrayHelper::map(SisrhCargo::find()->orderBy('descricao')->asArray()->all(), 'descricao', 'descricao'), 
         'filterWidgetOptions' => [
             'pluginOptions' => ['allowClear' => true],
         ],
         'filterInputOptions' => ['placeholder' => 'Cargo'],
         'width' => '17%',
    ],
    [
        'attribute' => 'categoria.nome',
        'width' => '10%',
         'filterType' => GridView::FILTER_SELECT2,
         'filter' => ArrayHelper::map(SisrhCategoria::find()->orderBy(
             'nome')->asArray()->all(), 'nome', 'nome'), 
         'filterWidgetOptions' => [
             'pluginOptions' => ['allowClear' => true],
         ],
         'filterInputOptions' => ['placeholder' => 'Categoria'],
    ],
    ['attribute' => 'telefone', 'width' => '10%','value' => function($data){ return implode(' | ' ,$data->telefone);}, 'visible' => Yii::$app->user->can( 'sisrhreports')],
    ['attribute' => 'emails','width' => '10%','value' => function($data){ return implode(', ' ,$data->emails);}],
    [
        'attribute' => 'situacao', 
        'width' => '10%',
        'class' => '\kartik\grid\BooleanColumn',
        'trueLabel' => 'Ativo', 
        'falseLabel' => 'Inativo'
    ],
];

$this->title = 'Contatos';
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sisrh-afastamento-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
        <?php
            /*echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'fontAwesome' => true,
                'filename' => 'contatos sisrh',
                'dropdownOptions' => [
                    'label' => 'Exportar',
                    'class' => 'btn btn-default'
                ]
            ]);*/
            $gridColumns[] = ['class' => 'yii\grid\ActionColumn',
                'controller' => 'sisrhpessoa',
                'template' => '{view}{update}',
                'headerOptions' => ['style' => 'width:5%'],
                'visible' => Yii::$app->user->can( 'sisrhpessoa'),
            ];
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'hover' => true,
                'responsiveWrap' => false,
                'filterModel' => $searchModel,
                'pjax' => true,
                'panel' => [
                    'type' => 'primary',
                ],
                'toolbar' =>  [
                    '{export}',
                    '{toggleData}',
                ],
                'emptyText' => 'Não há resultados para essa pesquisa',
            ]);
        ?>
    </div>
</div>

