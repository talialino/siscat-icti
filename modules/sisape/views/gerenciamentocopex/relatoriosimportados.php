<?php 

use yii\helpers\Html;
use yii\helpers\URl;
use yii\helpers\ArrayHelper;
use app\assets\SisapeAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\modules\sisape\models\SisapeProjeto;
use app\modules\sisrh\models\SisrhPessoa;
use kartik\select2\Select2;

SisapeAsset::register($this);

$this->title = 'Relatórios Importados';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisape'), 'url' => ['/'.strtolower('sisape')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-relatorios-importados">
    <?php
        Modal::begin([
            'header' => "<h3>$this->title</h3>",
            'id' => 'modal',
            'size' =>'modal-md',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>
    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <div class="sisape-projeto-search">

        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>
        <?= $form->field($model, 'id_projeto',['options' => ['class' => 'col-md-12']])->widget(Select2::className(), [
                'data' => ArrayHelper::map(SisapeProjeto::find()->select(['id_projeto','titulo'])->orderby('titulo')->all(),'id_projeto','titulo'),
                'options' => ['placeholder' => 'Selecione o projeto'],
                'pluginOptions' => ['allowClear' =>true]
        ]) ?>
        <?= $form->field($model, 'id_pessoa',['options' => ['class' => 'col-md-4']])->label('Coordenador(a)')->widget(Select2::className(), [
                'data' => ArrayHelper::map(SisrhPessoa::find()->orderby('nome')->all(),'id_pessoa','nome'),
                'options' => ['placeholder' => 'Selecione a pessoa'],
                'pluginOptions' => ['allowClear' =>true]
        ]) ?>
        <?= $form->field($model, 'tipo_projeto',['options' => ['class' => 'col-md-2']])->label('Tipo de Projeto')->dropDownList(SisapeProjeto::TIPO_PROJETO, ['prompt' => '']) ?>

        <div class="form-group col-md-3" style="margin-top:14px">
            <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="clearfix"></div>


        <?php ActiveForm::end(); ?>

    </div>

    <div class="sisape-relatorios">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsiveWrap' => false,
            'hover' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => 'Relatórios importados do sisape antigo e ainda não homologados',
            ],
            'toolbar' =>  false,
            'columns' => [
                ['attribute' => 'titulo', 'format' => 'raw', 'value' => function($data){
                    return Html::a($data->titulo,
                        Url::to(['/sisape/sisaperelatorio/view', 'id' => $data->id_relatorio]),
                        ['title' => 'Visualizar','data-toggle'=>'tooltip']);
                }],
                'pessoa.nome',
                'tipoProjeto',
                ['label' => 'Data de Aprovação', 'attribute' => 'data_aprovacao_copex','format' => 'raw', 'value' => function($data){
                    return Html::button('Aprovar Relatorio',[
                        'value'=>Url::to(['aprovarrelatorio', 'id' => $data->id_relatorio]), //<---- here is where you define the action that handles the ajax request
                        'class'=>'modalButton editModalButton',
                        'data-toggle'=>'tooltip',
                        'title' => "Aprovar Relatorio",
                    ]);
                }],
            ],
        ]); ?>
    </div>
</div>