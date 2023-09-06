<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\URl;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sispit\models\SispitOrientacaoAcademicaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="sispit-orientacao-academica-index">

    <?= GridView::widget([
        'dataProvider' => $model->search(array()),
        'responsiveWrap' => false,
        //'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Orientação Acadêmica',
            'footer' => $this->render('/sispitatividadecomplementar/_orientacao'.(!$plano->isRitAvailable() ? 'pit' : 'rit'),['model' => $atividadeComplementar]),
        ],
        'toolbar' =>  [
            ['content' => !$plano->isEditable() ? '' :
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['sispitorientacaoacademica/create','id' => $plano->id_plano_relatorio]),
                    'title' => 'Adicionar Orientação Acadêmica',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'columns' => [
            ['attribute' => 'semestre', 'group' => true, 'visible' => !Yii::$app->session->get('siscat_ano')->suplementar],
            'aluno.nome',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sispitorientacaoacademica',
                'template' => '{update} {delete}',
                'visible' => $plano->isEditable(),
                'buttons'=>[
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sispitorientacaoacademica/update', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-toggle'=>'tooltip',
                            'data-placement'=>'left',
                            'title'=>'Atualizar Orientação Acadêmica'
                        ]);
                        return $btn;
                    },
                ]
            ],
        ],
    ]); ?>
</div>
