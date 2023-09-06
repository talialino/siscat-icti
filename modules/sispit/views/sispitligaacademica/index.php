<?php

use yii\helpers\Html;
use yii\helpers\URl;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\assets\SispitAsset;
SispitAsset::register($this);

/* @var $this yii\web\View /*/
/* @var $model app\modules\sispit\models\SispitLigaAcademicaSearch */

?>
<div class="sispit-liga-academica-index">
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
        'dataProvider' => $model->search(),
        'responsiveWrap' => false,
        //'pjax' => true,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Liga Acadêmica',
            'footer' => false,
        ],
        'toolbar' =>  [
            ['content' => !$plano->isEditable() ? '' :
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['sispitligaacademica/create', 'id' => $model->id_plano_relatorio]),
                    'title' => 'Adicionar Liga Acadêmica',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'columns' => [
            ['attribute' => 'semestre', 'visible' => !Yii::$app->session->get('siscat_ano')->suplementar],
            'ligaAcademica.nome',
            'funcaoString',
            'carga_horaria',
            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sispitligaacademica',
                'contentOptions' => ['style' => 'width:70px;'],
                'template' => '{update} {delete}',
                'visible' => $plano->isEditable(),
                'buttons'=>[
                    'update' => function($url,$model,$key){
                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                            'value'=>Url::to(['sispitligaacademica/update', 'id' => $key]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-toggle'=>'tooltip',
                            'data-placement'=>'left',
                            'title'=>'Atualizar Liga Acadêmica'
                        ]);
                        return $btn;
                    },
                ]
            ],
        ],
    ]); ?>
</div>
