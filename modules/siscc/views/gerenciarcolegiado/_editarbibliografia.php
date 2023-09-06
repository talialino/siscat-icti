<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  [
            ['content' => 
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['/siscc/sisccprogramacomponentecurricularbibliografia/create', 'id' => $model->id_programa_componente_curricular, 'colegiado' => 1]),
                    'title' => 'Adicionar Bibliografia',
                    'class' => 'btn btn-success modalButton'
                    ]),
            ],
        ],
        'columns' => [
            ['attribute' => 'tipo', 'group' => true],
            'referencia',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function($url,$model,$key){
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '0',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                        ];
                        return Html::a($icon, Url::toRoute(['deletebibliografia', 'id_programa_componente_curricular' => $model->id_programa_componente_curricular,'id_bibliografia' => $model->id_bibliografia]), $options);
                    },
                ],
            ],
        ],
    ]);
?>
<?php if($model->isEditable() && $model->situacao != 1):?>
<?php ActiveForm::begin([
    'action' => Url::to(['/siscc/sisccprogramacomponentecurricular/submeter','id' => $model->id_programa_componente_curricular])
]); ?>
    <?= Html::submitButton($model->situacao == 0 ? 'Submeter' : 'Encaminhar ao parecerista', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); 
endif;
?>