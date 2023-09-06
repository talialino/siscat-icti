<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use kartik\datecontrol\DateControl;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeProjeto */
/* @var $form yii\widgets\ActiveForm */

$displayTipoExtensao = $model->tipo_projeto == $model::EXTENSAO ? null : 'display: none';

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
<div class="sisape-projeto-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
    <?= Tabs::widget([
        'options' => ['class' => 'responsive'],
        'itemOptions' => ['class' => 'responsive'],
        'items' => [
            [
                'label' => 'Informações Básicas',
                'active' => $tab == 0,
                'content' => <<< BASICAS

                    {$form->field($model, 'situacao')->label(false)->hiddenInput()}

                    {$form->field($model, 'tipo_projeto',['options' => ['class' => 'col-md-4']])->dropDownList($model::TIPO_PROJETO,['onclick'=>'$(this).val() != '.$model::EXTENSAO.'?$("#tipo_extensao").hide():$("#tipo_extensao").show();'])}

                    {$form->field($model, 'tipo_extensao', ['options' => ['class'=> 'col-md-4','id' => 'tipo_extensao', 'style' => $displayTipoExtensao]])->dropDownList($model::TIPO_EXTENSAO)}

                    {$form->field($model, 'disponivel_site',['options' => ['class' => 'col-md-4']])->dropDownList([1 => 'Sim', 0 => 'Não'])}

                    {$form->field($model, 'titulo',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 2])}

                    {$form->field($model, 'area_atuacao',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 2])}

                    {$form->field($model, 'resumo',['options' => ['class' => 'col-md-12']])->textarea(['rows' => 10])}

                    {$form->field($model, 'data_inicio',['options' => ['class' => 'col-md-4']])->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,])}

                    {$form->field($model, 'data_fim',['options' => ['class' => 'col-md-4']])->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,])}
                    
                    <div class='form-group col-md-12' style='padding-top: 20px;'>
                        <button type='submit' class='btn btn-success'>Salvar</button>
                    </div>

                    <div class="clearfix"></div>
BASICAS
            ],
            [
                'label' => 'Pesquisa',
                'active' => $tab == 1,
                'visible' => !$model->isNewRecord && ($model->tipo_projeto == $model::PESQUISA),
                'content' => <<< PESQUISA

                    {$form->field($model, 'local_execucao')->textarea(['rows' => 2])}
                
                    {$form->field($model, 'parcerias')->textarea(['rows' => 2])}
                
                    {$form->field($model, 'infraestrutura')->textarea(['rows' => 2])}
                
                    {$form->field($model, 'submetido_etica')->dropDownList([1 => 'Sim', 0 => 'Não'], ['prompt' => ''])}
                
                    {$form->field($model, 'introducao')->textarea(['rows' => 6])}
                
                    {$form->field($model, 'justificativa')->textarea(['rows' => 6])}
                
                    {$form->field($model, 'objetivos')->textarea(['rows' => 6])}
                
                    {$form->field($model, 'metodologia')->textarea(['rows' => 6])}
                
                    {$form->field($model, 'resultados_esperados')->textarea(['rows' => 6])}
                
                    {$form->field($model, 'orcamento')->textarea(['rows' => 6])}
                
                    {$form->field($model, 'referencias')->textarea(['rows' => 6])}

                    <div class='form-group col-md-12' style='padding-top: 20px;'>
                        <button type='submit' class='btn btn-success'>Salvar</button>
                    </div>
                    <div class="clearfix"></div>
PESQUISA
            ],
            [
                'label' => 'Financiamento',
                'active' => $tab == 2,
                'visible' => !$model->isNewRecord,
                'content' => 
                    GridView::widget([
                        'dataProvider' => new ActiveDataProvider(['query' => $model->getSisapeFinanciamentos(),
                            'pagination' => false,
                        ]),
                    
                        'panel' => [
                            'type' => 'primary',
                            'after' => false,
                            'footer' => false,
                        ],
                        'toolbar' =>  [
                            ['content' => 
                            Html::button('<i class="glyphicon glyphicon-plus"></i>',
                                [
                                'value' =>Url::to(['sisapefinanciamento/create', 'id' => $model->id_projeto]),
                                'title' => 'Adicionar Financiamento',
                                'class' => 'btn btn-success modalButton'
                                ]),
                            ],
                        ],
                        'columns' => [
                            'origem',
                            'valor:currency',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'controller' => 'sisapefinanciamento',
                                'contentOptions' => ['style' => 'width:100px;'],
                                'template' => '{update} {delete}',  
                                'visible' => true,              
                                'buttons'=>[
                                    'update' => function($url,$model,$key){
                                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                                            'value'=>Url::to(['sisapefinanciamento/update', 'id' => $key]), 
                                            'class'=>'modalButton editModalButton',
                                            'data-placement'=>'bottom',
                                            'title'=>'Atualizar financiamento'
                                        ]);
                                        return $btn;
                                    },
                                ]
                            ],
                        ],
                    ])
            ],
            [
                'label' => 'Equipe Executora',
                'active' => $tab == 3,
                'visible' => !$model->isNewRecord,
                'content' => 
                    GridView::widget([
                        'dataProvider' => new ActiveDataProvider(['query' => $model->getSisapeProjetoIntegrantes(),
                            'pagination' => false,
                        ]),
                    
                        'panel' => [
                            'type' => 'primary',
                            'after' => false,
                            'footer' => false,
                        ],
                        'toolbar' =>  [
                            ['content' => 
                            Html::button('<i class="glyphicon glyphicon-plus"></i>',
                                [
                                'value' =>Url::to(['sisapeprojetointegrante/create', 'id' => $model->id_projeto]),
                                'title' => 'Adicionar Membro da Equipe',
                                'class' => 'btn btn-success modalButton'
                                ]),
                            ],
                        ],
                        'columns' => [
                            'nome',
                            'funcao',
                            'vinculoString',
                            'carga_horaria',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'controller' => 'sisapeprojetointegrante',
                                'contentOptions' => ['style' => 'width:100px;'],
                                'template' => '{update} {delete}',  
                                'visible' => true,              
                                'buttons'=>[
                                    'update' => function($url,$model,$key){
                                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                                            'value'=>Url::to(['sisapeprojetointegrante/update', 'id' => $key]), 
                                            'class'=>'modalButton editModalButton',
                                            'data-placement'=>'bottom',
                                            'title'=>'Atualizar membro da equipe'
                                        ]);
                                        return $btn;
                                    },
                                ]
                            ],
                        ],
                    ])
            ],
            [
                'label' => 'Cronograma',
                'active' => $tab == 4,
                'visible' => !$model->isNewRecord,
                'content' => 
                    GridView::widget([
                        'dataProvider' => new ActiveDataProvider(['query' => $model->getSisapeAtividades(),
                            'pagination' => false,
                        ]),
                    
                        'panel' => [
                            'type' => 'primary',
                            'after' => false,
                            'footer' => false,
                        ],
                        'toolbar' =>  [
                            ['content' => 
                            Html::button('<i class="glyphicon glyphicon-plus"></i>',
                                [
                                'value' =>Url::to(['sisapeatividade/create', 'id' => $model->id_projeto]),
                                'title' => 'Adicionar Cronograma',
                                'class' => 'btn btn-success modalButton'
                                ]),
                            ],
                        ],
                        'columns' => [
                            'descricao_atividade',
                            'data_inicio:date',
                            'data_fim:date',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'controller' => 'sisapeatividade',
                                'contentOptions' => ['style' => 'width:100px;'],
                                'template' => '{update} {delete}',  
                                'visible' => true,              
                                'buttons'=>[
                                    'update' => function($url,$model,$key){
                                        $btn = Html::button("<span class='glyphicon glyphicon-pencil'></span>",[
                                            'value'=>Url::to(['sisapeatividade/update', 'id' => $key]), 
                                            'class'=>'modalButton editModalButton',
                                            'data-placement'=>'bottom',
                                            'title'=>'Atualizar cronograma'
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
                'active' => $tab == 5,
                'visible' => !$model->isNewRecord && $model->isEditable() && $model->situacao != 1,
                'content' =>
                    Html::button('Submeter projeto',
                        [
                        'value' =>Url::to(['submeter', 'id' => $model->id_projeto]),
                        'title' => 'Submeter o projeto para avaliação',
                        'class' => 'btn btn-primary modalButton'
                    ])
            ],
            [
                'label' => 'Não Homologar',
                'active' => false,
                'visible' => ($model->situacao > 0 && ($model->situacao != 12 || $model->situacao != 14)) && Yii::$app->user->can('sisapeAdministrar'),
                'content' =>
                    Html::button('Definir projeto como Não Homologado',
                        [
                        'value' =>Url::to(['naohomologar', 'id' => $model->id_projeto]),
                        'title' => 'Definir projeto como Não Homologado',
                        'class' => 'btn btn-danger modalButton'
                    ])
            ],
        ]
    ]);?>
    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>
