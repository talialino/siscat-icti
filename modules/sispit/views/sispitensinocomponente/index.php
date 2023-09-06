<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitEnsinoComponenteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="sispit-ensino-componente-index">

    <?= GridView::widget([
        'dataProvider' => $model->search(array()),
        //'pjax' => true,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Componentes',
            'footer' => false,
        ],
        'toolbar' =>  [
            ['content' => !$plano->isEditable() ? '' :
                
                Html::button('<i class="glyphicon glyphicon-import"></i>',
                    [
                    'value' =>Url::to(['sispitensinocomponente/import', 'id' => $plano->id_plano_relatorio]),
                    'title' => 'Importar componentes do SISCC',
                    'class' => 'btn btn-primary modalButton'
                    ]).($plano->isRitAvailable() ? '' :
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['sispitensinocomponente/create', 'id' => $plano->id_plano_relatorio]),
                    'title' => 'Adicionar Componente',
                    'class' => 'btn btn-success modalButton'
                ])),
            ],
        ],
        'columns' => [

            ['attribute' => 'semestre', 'group' => true, 'visible' => !Yii::$app->session->get('siscat_ano')->suplementar],
            'nivelGraduacao',
            'componenteCurricular.codigoNome',
            'ch_teorica',
            'ch_pratica',
            'ch_estagio',
            'total',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
             'visible' => $plano->isEditable(),
             'controller' => 'sispitensinocomponente',
             //'width' => '10%',
             'contentOptions' => ['style' => 'width:70px;'],
             'buttons'=>[
                'update' => function($url,$model,$key){
                    $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                        'value'=>Yii::$app->urlManager->createUrl('/sispit/sispitensinocomponente/update?id='.$key), //<---- here is where you define the action that handles the ajax request
                        'class'=>'modalButton editModalButton',
                        'data-toggle'=>'tooltip',
                        'data-placement'=>'left',
                        'title'=>'Atualizar Componente'
                    ]);
                    return $btn;
                }
            ]
            ],
        ],
    ]); ?>
</div>
