<?php 

use yii\helpers\Html;
use yii\helpers\URl;
use app\assets\SispitAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\modules\sispit\models\SispitParecer;
use app\modules\sispit\models\SispitAno;
use yii\helpers\ArrayHelper;
use app\modules\sisrh\models\SisrhComissao;

SispitAsset::register($this);

$this->title = 'Comissão de Avaliação de PIT e RIT';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-default-index">
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
   
<?php
        Modal::begin([
            'header' => "<h3>$this->title</h3>",
            'id' => 'modal',
            'size' =>'modal-md',
            'options' => ['tabindex' => false,],
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>
    <div class="sispit-cac-docentes">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'responsiveWrap' => false,
            'hover' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => 'Docentes',
            ],
            'toolbar' =>  false,
            'columns' => [
                [
                    'attribute' => 'pessoa.nome',
                ],
                [
                    'attribute' => 'id_ano',
                    'filter' => ArrayHelper::map(SispitAno::find()->orderby('id_ano DESC')->all(),'id_ano','string'),
                    'value' => function($model, $key, $index, $widget){return $model->ano;},
                ],
                [
                    'attribute' => 'status',
                    'filter' => [
                        5 => 'PIT aguardando a CAC definir parecerista',
                        6 => 'PIT aguardando avaliação do parecerista da CAC',
                        7 => 'PIT aprovado pelo parecerista da CAC',
                        8 => 'PIT necessita de correções de acordo com parecerista da CAC',
                        15 => 'RIT aguardando a CAC definir parecerista',
                        16 => 'RIT aguardando avaliação do parecerista da CAC',
                        17 => 'RIT aprovado pelo parecerista da CAC',
                        18 => 'RIT necessita de correções de acordo com parecerista da CAC',
                    ],
                    'value' => function($model, $key, $index, $widget){return $model->situacao;},
                ],
                [
                    'label' => 'Parecerista',
                    'attribute' => 'parecerista',
                    'filter' => ArrayHelper::map(SisrhComissao::find()->where(['eh_comissao_pit_rit' => 1])->one()->pessoas,'id_pessoa','nome'),
                    'format' => 'raw',
                    'value' => function($model){
                        if($model->id_plano_relatorio){
                            switch($model->status % 10){
                                case 5:
                                    return Html::button('Definir parecerista',[
                                        'value'=>Url::to(['definirparecerista', 'id' => $model->id_plano_relatorio]), //<---- here is where you define the action that handles the ajax request
                                        'class'=>'modalButton editModalButton',
                                        'data-toggle'=>'tooltip',
                                        'title' => 'Definir Parecerista',
                                    ]);
                                case 6:
                                case 7:
                                case 8:
                                    $parecer = SispitParecer::find()->where(['id_plano_relatorio' => $model->id_plano_relatorio,
                                        'tipo_parecerista' => SispitParecer::PARECERISTA_CAC, 'atual' => 1, 'pit_rit' => ($model->status > 9 ? 1 : 0)
                                    ])->one();
                                    if($parecer->pessoa->getSisrhComissoes()->where(['eh_comissao_pit_rit' => 1])->exists())
                                        return Html::button($parecer->pessoa->nome,[
                                            'value'=>Url::to(['definirparecerista', 'id' => $model->id_plano_relatorio]), //<---- here is where you define the action that handles the ajax request
                                            'class'=>'modalButton editModalButton',
                                            'data-toggle'=>'tooltip',
                                            'title' => 'Alterar Parecerista',
                                        ]);
                                    return $parecer->pessoa->nome;
                            }
                        }
                        return false;
                    }
                ],
            ],
        ]); ?>
    </div>
</div>