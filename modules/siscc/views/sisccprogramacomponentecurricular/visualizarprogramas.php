<?php 

use yii\helpers\Html;
use yii\helpers\URl;
use app\assets\SisccAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use app\modules\siscc\models\SisccParecer;

SisccAsset::register($this);

$this->title = 'Visualizar Programas';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-default-visualizar-programas">
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
    <div class="siscc-visualizar">
        <?= $this->render('_search', ['model' => $searchModel]) ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsiveWrap' => false,
            'hover' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => '',
            ],
            'toolbar' =>  false,
            'columns' => [
                'componente',
                'colegiado',
                'situacaoString',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['style' => 'width:80px;'],
                    'template' => '{parecer}{pdf}',
                    'buttons' => [
                        'pdf' => function($url,$model,$key){
                            $btn = Html::a(Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']),
                            Url::to(['/siscc/sisccprogramacomponentecurricular/pdf', 'id' => $model->id_programa_componente_curricular]), [
                                'data-placement'=>'bottom',
                                'title'=>'Baixar PDF',
                            ]);
                            return $btn;
                        },
                        'parecer' => function($url, $model, $key){
                            return Html::button("<span class='glyphicon glyphicon-check'></span>",[
                                'value'=>Url::to(['/siscc/sisccparecer/view', 'id' => $key]), 
                                'class'=>'modalButton editModalButton',
                                'data-placement'=>'bottom',
                                'title'=>'Visualizar Pareceres'
                            ]);
                        }
                    ],
                    'visibleButtons' => [
                        'pdf' => function($model){
                            return $model->situacao > 0;
                        }, 
                        'parecer' => function($model){
                            return $model->situacao > 2;
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>