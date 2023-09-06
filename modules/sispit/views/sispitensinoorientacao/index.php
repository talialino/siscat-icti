<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\sispit\models\SispitEnsinoOrientacao;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitEnsinoOrientacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="sispit-ensino-orientacao-index">

    <?= GridView::widget([
        'dataProvider' => $model->search(array()),
        //'pjax' => true,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Outras Orientações',
            'footer' => false,
        ],
        'toolbar' =>  [
            ['content' => !$plano->isEditable() ? '' :
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['sispitensinoorientacao/create','id' => $plano->id_plano_relatorio]),
                    'title' => 'Adicionar Outras Orientações',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'columns' => [
            ['attribute' => 'semestre', 'group' => true, 'visible' => !Yii::$app->session->get('siscat_ano')->suplementar],
            'tipoOrientacao',
            'aluno.nome',
            'projeto:text',
            'carga_horaria',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'controller' => 'sispitensinoorientacao',
                'visible' => $plano->isEditable(),
                'buttons'=>[
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Yii::$app->urlManager->createUrl('/sispit/sispitensinoorientacao/update?id='.$key), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-toggle'=>'tooltip',
                            'data-placement'=>'left',
                            'title'=>'Atualizar Outras Orientações'
                        ]);
                        return $btn;
                    }
                ]
            ],
        ],
    ]); ?>
</div>
