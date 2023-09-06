<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisrh\models\SisrhSetorMembroAutomaticoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Membros Automáticos';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisrh')), 'url' => ['/'.strtolower(Yii::t('app', 'sisrh'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-setor-membro-automatico-index">

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

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  [
            ['content' => 
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['create']),
                    'title' => 'Adicionar Membro Automático',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'setorOrigem.nome',
            'funcaoOrigem',
            'setorDestino.nome',
            'funcaoDestino',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sisrhsetormembroautomatico',
                'contentOptions' => ['style' => 'width:100px;'],
                'template' => '{update} {delete}',  
                'visible' => true,              
                'buttons'=>[
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sisrhsetormembroautomatico/update', 'id' => $key]), 
                            'class'=>'modalButton editModalButton',
                            'data-placement'=>'bottom',
                            'title'=>'Atualizar Membro automático'
                        ]);
                        return $btn;
                    },
                ]
            ],
        ],
    ]); ?>
    </div>
</div>
