<?php

use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;


/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricular */

echo DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary'],
        'enableEditMode' => false,
        'attributes' => [
            [
            'columns' => [
                'componente',
                'colegiado',
                'semestreString',
                ]
            ],
            ['label'=>'Ementa', 'value'=> $model->componenteCurricular->ementa],
            'situacaoString',
            'objetivo_geral:raw',
            'objetivos_especificos:raw',
            'conteudo_programatico:raw',         
            ['label' => 'Bibliografia','format' => 'raw', 'value' =>
                GridView::widget([
                    'dataProvider' => new ActiveDataProvider([
                            'query' => $model->getSisccProgramaComponenteCurricularBibliografias(),
                            'pagination' => false,
                        ]),
                    'responsiveWrap' => false,
                    'summary' => false,
                    'rowOptions' => ['class' => 'wordBreak'],
                    'columns' => [
                        ['attribute' => 'tipo', 'group' => true],
                        'referencia',
                    ],
                ])
            ],
            ['label' => 'Docentes Responsáveis','format' => 'raw', 'value' =>
                GridView::widget([
                    'dataProvider' => new ActiveDataProvider([
                            'query' => $model->getPessoas(),
                            'pagination' => false,
                        ]),
                    //'pjax' => true,
                    'responsiveWrap' => false,
                    'summary' => false,
                    'showHeader'=> false,
                    /*'panel' => [
                        'type' => 'primary',
                        'after' => false,
                    ],*/
                    //'toolbar' =>  false,
                    'columns' => [
                        'nome'
                    ],
                ])
            ],
            [
                'columns'=>
                [
                    'data_aprovacao_colegiado:date',
                    'data_aprovacao_coordenacao:date',
                ]
            ],
        ],
    ]);
?>