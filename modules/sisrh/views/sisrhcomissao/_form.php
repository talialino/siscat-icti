<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use kartik\grid\GridView;
use dosamigos\tinymce\TinyMce;
use kartik\datecontrol\DateControl;
use app\modules\sisrh\models\SisrhComissao;
use app\modules\sisrh\models\SisrhComissaoPessoa;
use app\assets\SisrhAsset;
use yii\bootstrap\Modal;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhComissao */
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
<div class="sisrh-setor-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
    <?= Tabs::widget([
        'options' => ['class' => 'responsive'],
        'itemOptions' => ['class' => 'responsive'],
    'items' => [
        [
            'label' => 'Dados da Comissão',
            'content' => "
            <div class='col-md-12 tabs-conteudo'>

            <div class='col-md-6'>{$form->field($model, 'nome')->textInput(['maxlength' => true])}</div>

            <div class='col-md-6'>{$form->field($model, 'sigla')->textInput(['maxlength' => true])}</div>

            <div class='col-md-6'>{$form->field($model, 'data_inicio')->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,])}</div>

            <div class='col-md-6'>{$form->field($model, 'data_fim')->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,])}</div>

            ".(Yii::$app->user->can('siscatAdministrar') ?
            "<div class='col-md-6'> {$form->field($model, 'eh_comissao_pit_rit')->radioList([Yii::t('app','No'),Yii::t('app','Yes')])}</div>

            <div class='col-md-6'> {$form->field($model, 'eh_comissao_liga')->radioList([Yii::t('app','No'),Yii::t('app','Yes')])}</div>
            " : '')."
            
            <div class='form-group col-md-12'>".Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-success'])."</div></div>"
        ],
        [
            'label' => Yii::t('app','Composition'),
            'visible' => !$model->isNewRecord,
            'active' => !$model->isNewRecord,
            'content' => !$model->isNewRecord ? "<div class='col-md-12  tabs-conteudo'>
                <div class='table-responsive'>".GridView::widget([
                    'dataProvider' => $dataProvider,
                    'panel' => [
                        'type' => 'primary',
                        'after' => false,
                        'footer' => false,
                    ],
                    'toolbar' =>  [
                        ['content' => 
                        Html::button('<i class="glyphicon glyphicon-plus"></i>',
                            [
                            'value' =>Url::to(['/sisrh/sisrhcomissaopessoa/create','id_comissao' => $model->id_comissao]),
                            'title' => "Adicionar Pessoa - $model->nome",
                            'class' => 'btn btn-success modalButton'
                            ]),
                        ],
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'pessoa.nome',
                            'value' => function($model){ 
                                return $model->pessoa->temOcorrenciaVigente() ? "<span class='ocorrenciaVigente'>{$model->pessoa->nome}</span>" : $model->pessoa->nome;
                            },
                            'format' => 'raw',
                        ],
                        ['attribute' => 'funcao','value' => function($data){return SisrhComissaoPessoa::FUNCOES[$data->funcao];}],
                    ['class' => 'yii\grid\ActionColumn',
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
                                return Html::a($icon, Url::toRoute(['/sisrh/sisrhcomissaopessoa/delete', 'comissao' => $model->id_comissao,'pessoa' => $model->id_pessoa]), $options);
                            }
                        ]
                    ],
                ],])."</div></div>" : ''
                    ],
                    [
                        'label' => 'Observações',
                        'visible' => !$model->isNewRecord,
                        'content' => !$model->isNewRecord ? "<div class='col-md-12  tabs-conteudo'>" . $form->field($model, 'observacao',['template' => "\n{input}\n{hint}\n{error}",])->widget(TinyMce::class, [
                                'options' => ['rows' => 6],
                                
                                'language' => 'pt_BR',
                                'clientOptions' => [
                                    'menubar' => false,
                                    'toolbar' => 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent'
                                ]
                            ])."<div class='form-group'>".Html::submitButton('Salvar', ['class' => 'btn btn-success'])."</div></div>" : ''
                    ]
    ]])?>

    <?php ActiveForm::end(); ?>

</div>