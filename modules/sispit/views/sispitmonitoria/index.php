<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitMonitoriaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider **/

?>
<div class="sispit-monitoria-index">

    <?= GridView::widget([
        'dataProvider' => $model->search(array()),
        'responsiveWrap' => false,
        //'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Monitoria',
            'footer' => false,
        ],
        'toolbar' =>  [
            ['content' => !$plano->isEditable() ? '' :
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['sispitmonitoria/create','id' => $plano->id_plano_relatorio]),
                    'title' => 'Adicionar Monitoria',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'columns' => [
            ['attribute' => 'semestre', 'group' => true, 'visible' => !Yii::$app->session->get('siscat_ano')->suplementar],
            'aluno.nome',
            'componenteCurricular.nome',
            'carga_horaria',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'controller' => 'sispitmonitoria',
                'contentOptions' => ['style' => 'width:70px;'],
                'visible' => $plano->isEditable(),
                'buttons'=>[
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Yii::$app->urlManager->createUrl('/sispit/sispitmonitoria/update?id='.$key), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-toggle'=>'tooltip',
                            'data-placement'=>'left',
                            'title'=>'Atualizar Monitoria'
                        ]);
                        return $btn;
                    }
                ]
            ],
        ],
    ]); ?>
</div>
