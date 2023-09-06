<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaLigaAcademica */
/* @var $form yii\widgets\ActiveForm */

?>
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
<div class="sisliga-liga-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'enctype' => 'multipart/form-data'],]); ?>
    <?= Tabs::widget([
        'options' => ['class' => 'responsive'],
        'itemOptions' => ['class' => 'responsive'],
        'items' => [
            [
                'label' => 'Informações',
                'active' => $tab == 0,
                'content' => $this->render('_forminformacoes',['form' => $form, 'model' => $model]),
            ],
            [
                'label' => 'Membros',
                'active' => $tab == 1,
                'visible' => !$model->isNewRecord,
                'content' => 
                    GridView::widget([
                        'dataProvider' => new ActiveDataProvider(['query' => $model->getSisligaLigaIntegrantes(),
                            'pagination' => false,
                        ]),
                    
                        'panel' => [
                            'type' => 'primary',
                            'after' => false,
                            'footer' => false,
                        ],
                        'toolbar' =>  [
                            ['content' => 
                            Html::button('<i class="glyphicon glyphicon-plus"></i> Novo membro',
                                [
                                'value' =>Url::to(['sisligaligaintegrante/create', 'id' => $model->id_liga_academica]),
                                'title' => 'Adicionar Membro',
                                'class' => 'btn btn-success modalButton'
                                ]),
                            ],
                        ],
                        'columns' => [
                            'nome',
                            'funcao',
                            'instituicao',
                            'carga_horaria',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'controller' => 'sisligaligaintegrante',
                                'contentOptions' => ['style' => 'width:100px;'],
                                'template' => '{update} {delete}',  
                                'visible' => true,              
                                'buttons'=>[
                                    'update' => function($url,$model,$key){
                                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                                            'value'=>Url::to(['sisligaligaintegrante/update', 'id' => $key]), 
                                            'class'=>'modalButton editModalButton',
                                            'data-placement'=>'bottom',
                                            'title'=>'Atualizar membro'
                                        ]);
                                        return $btn;
                                    },
                                ]
                            ],
                        ],
                    ])
            ],
            [
                'label' => 'Submeter',
                'active' => $tab == 2,
                'visible' => !$model->isNewRecord && $model->isEditable() && $model->situacao != 1,
                'content' =>
                    Html::button('Submeter liga',
                        [
                        'value' =>Url::to(['submeter', 'id' => $model->id_liga_academica]),
                        'title' => 'Submeter a liga para avaliação',
                        'class' => 'btn btn-primary modalButton'
                    ])
            ],
            [
                'label' => 'Não Homologar',
                'active' => false,
                'visible' => ($model->situacao > 0 && ($model->situacao != 7 && $model->situacao != 9)) && Yii::$app->user->can('sisligaAdministrar'),
                'content' =>
                    Html::button('Definir liga como Não Homologada',
                        [
                        'value' =>Url::to(['naohomologar', 'id' => $model->id_liga_academica]),
                        'title' => 'Definir liga como Não Homologada',
                        'class' => 'btn btn-danger modalButton'
                    ])
            ],
        ]
    ]);?>
    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>
