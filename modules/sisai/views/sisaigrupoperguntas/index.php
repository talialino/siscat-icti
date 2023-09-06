<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sisai\models\SisaiQuestionario */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="sisai-grupo-perguntas-index">

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider(['query' => $model->getSisaiGrupoPerguntas(), 'pagination' => false, 'sort' => false]),
        'pjax' => true,
        'panel' => [
            'type' => 'primary',
        ],
        'toolbar' =>  [
            ['content' => 
                Html::a('<i class="glyphicon glyphicon-plus"></i>',['/sisai/sisaigrupoperguntas/create', 'id' => $model->id_questionario], ['title' => Yii::t('app', 'Adicionar Grupo Perguntas'), 'class' => 'btn btn-success', ]),
            ],
        ],
        'columns' => [

            'id_grupo_perguntas',
            'titulo',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'sisaigrupoperguntas',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>
    </div>
</div>
