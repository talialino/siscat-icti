<?php

use yii\helpers\Html;
use yii\helpers\URl;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\assets\SispitAsset;
SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitAtividadesAdministrativasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Atividades Administrativas';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-atividades-administrativas-index">

    <h1 class="cabecalho"><span><?= Html::encode($this->title) ?></span></h1>
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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsiveWrap' => false,
        //'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'footer' => false,
        ],
        'toolbar' =>  [
            ['content' => !$plano->isEditable() ? '' :
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['sispitatividadesadministrativas/create', 'id' => $searchModel->id_plano_relatorio]),
                    'title' => 'Adicionar Atividades Administrativas',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'columns' => [
            ['attribute' => 'semestre','width' => '10%', 'group' => true, 'visible' => !Yii::$app->session->get('siscat_ano')->suplementar],
            [
                'attribute' => 'tipoAtividade',
                'width' => '30%',
            ],
            ['attribute' => 'descricao', 'width' => '40%'],
            ['attribute' => 'carga_horaria', 'width' => '10%'],

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sispitatividadesadministrativas',
                'template' => '{update} {delete}',
                'visible' => $plano->isEditable(),
                'buttons'=>[
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sispitatividadesadministrativas/update', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-toggle'=>'tooltip',
                            'data-placement'=>'left',
                            'title'=>'Atualizar Atividade Administrativa'
                        ]);
                        return $btn;
                    },
                ]
            ],
        ],
    ]); ?>
</div>
