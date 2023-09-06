<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use kartik\grid\GridView;
use dosamigos\tinymce\TinyMce;
use app\modules\sisrh\models\SisrhSetor;
use app\modules\sisrh\models\SisrhSetorPessoa;
use app\assets\SisrhAsset;
use yii\bootstrap\Modal;

SisrhAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetor */
/* @var $form yii\widgets\ActiveForm */

$setorPermissao = Yii::$app->user->can('sisrhsetor');
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
                'label' => Yii::t('app','Sector Data'),
                'visible' => Yii::$app->user->can('sisrhAdministrar'),
                'content' => "<div class='col-md-12 tabs-conteudo'>

                <div class='col-md-4'>{$form->field($model, 'nome')->textInput(['maxlength' => true, 'readonly' => !Yii::$app->user->can('siscatAdministrar')])}</div>

                <div class='col-md-4'>{$form->field($model, 'id_setor_responsavel')->dropDownList(ArrayHelper::map(SisrhSetor::find()->all(),'id_setor','nome'),['prompt' => ''])}</div>

                <div class='col-md-4'>{$form->field($model, 'codigo')->textInput()}</div>

                <div class='col-md-4'>{$form->field($model, 'sigla')->textInput(['maxlength' => true])}</div>

                <div class='col-md-4'>{$form->field($model, 'email')->textInput()}</div>

                <div class='col-md-4'>{$form->field($model, 'ramais')->textInput()}</div>

                <div class='col-md-4'> {$form->field($model, 'eh_colegiado')->radioList([Yii::t('app','No'),Yii::t('app','Yes')])}</div>

                <div class='col-md-4'>{$form->field($model, 'eh_nucleo_academico')->radioList([Yii::t('app','No'),Yii::t('app','Yes')])}</div>
                
                <div class='col-md-12'><div class='form-group'>".Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-success'])."</div></div></div>"
            ],
            [
                'label' => Yii::t('app','Composition'),
                'visible' => !$model->isNewRecord && $setorPermissao,
                'active' => !$model->isNewRecord,
                'content' =>  !$model->isNewRecord ? "<div class='col-md-12  tabs-conteudo'>
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
                                'value' =>Url::to(['/sisrh/sisrhsetorpessoa/create','id_setor' => $model->id_setor]),
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
                            ['attribute' => 'funcao','value' => function($data){return SisrhSetorPessoa::FUNCOES[$data->funcao];}],
                        ['class' => 'yii\grid\ActionColumn',
                            'template' => '{view}{delete}',
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
                                    return Html::a($icon, Url::toRoute(['/sisrh/sisrhsetorpessoa/delete', 'setor' => $model->id_setor,'pessoa' => $model->id_pessoa]), $options);
                                },
                                'view' => function($url,$model,$key){
                                    $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-eye-open"]);
                                    $options = [
                                        'title' => 'Visualizar pessoa',
                                        'aria-label' => 'Visualizar pessoa',
                                        'data-pjax' => '0',
                                    ];
                                    return Html::a($icon, Url::toRoute(['/sisrh/sisrhpessoa/view', 'id' => $model->id_pessoa]), $options);
                                }
                            ],
                            'visibleButtons' => [
                                'delete' => function($model){
                                    return !$model->membroAutomatico;
                                },
                            ]
                        ],
                    ],])."</div></div>" : ''
                ],
                [
                    'label' => 'Observações',
                    'visible' => !$model->isNewRecord && $setorPermissao,
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
    <div class="clearfix">
</div>