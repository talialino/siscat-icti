<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'pjax' => true,
        'responsiveWrap' => false,
        'panel' => [
            'type' => 'primary',
            'after' => false,
        ],
        'toolbar' =>  [
            ['content' => 
                Html::button('<i class="glyphicon glyphicon-plus"></i>',
                    [
                    'value' =>Url::to(['/siscc/sisccprogramacomponentecurricularbibliografia/create', 'id' => $model->id_programa_componente_curricular]),
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
                'controller' => 'sisccprogramacomponentecurricularbibliografia',
                'template' => '{delete}',  
                'visible' => $model->isEditable(),
            ],
        ],
    ]); 
?>
<?php if($model->isEditable() && $model->situacao != 1):?>
    <?php ActiveForm::begin([
        'action' => Url::to(['submeter','id' => $model->id_programa_componente_curricular])
    ]); ?>
            <?= Html::submitButton('Finalizar Preenchimento', ['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); 
endif;
?>
    