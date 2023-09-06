<?php 

use yii\helpers\Html;
use yii\helpers\URl;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\modules\sisrh\models\SisrhSetor;

//Somente exibe a tabela se houverem resultados
if($dataProvider->getCount() > 0):

?>
<div class="sisape-definir-nucleo">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsiveWrap' => false,
        'hover' => true,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Projetos aguardando definição de núcleo',
        ],
        'toolbar' =>  false,
        'columns' => [
            ['attribute' => 'titulo', 'format' => 'raw', 'value' => function($data){
                return Html::a($data->titulo,
                    Url::to(['/sisape/sisapeprojeto/view', 'id' => $data->id_projeto]),
                    ['title' => 'Visualizar','data-toggle'=>'tooltip']);
            }],
            'pessoa.nome',
            [
                'attribute' => 'id_setor',
                'class' => 'kartik\grid\EditableColumn',
                'editableOptions' => [
                    'asPopover' => false,
                    'inputType' => 'dropDownList',
                    'data' => ArrayHelper::map(SisrhSetor::find()->where(['eh_nucleo_academico' => 1])->all(), 'id_setor','nome'),
                    'options' => ['class'=>'form-control', 'placeholder'=>'Selecione o núcleo'],
                    'formOptions' => ['action' => ['definirnucleo']],
                ],
            ],
        ],
    ]); ?>
</div>
<?php endif;?>