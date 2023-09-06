<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\datecontrol\DateControl;
use yii\web\JsExpression;
use unclead\multipleinput\MultipleInput;
use \dektrium\user\models\User;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use app\modules\sisrh\models\SisrhCargo;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhClasseFuncional;
use yii\bootstrap\Modal;
use app\assets\SisrhAsset;
use yii\widgets\MaskedInput;

SisrhAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhPessoa */
/* @var $form yii\widgets\ActiveForm */

//array com estados
$layout = <<< HTML
    <div class='form-group field-sisrhpessoa-dt_nascimento'> <label class='control-label'>{$model->attributeLabels()['dt_nascimento']}</label>
    {picker}{input}
    </div>
HTML;
// $estados = ArrayHelper::map(SisrhEstado::find()->all(),'id_estado','nome');

//Os valores abaixo são utilizados pelos campos Select2
// $url = \yii\helpers\Url::to(['sisrhmunicipio/municipiolist']);
// $naturalidade = isset($model->naturalidade) ? $model->naturalidade->nome : '';
// $municipio = isset($model->municipio) ? $model->municipio->nome : '';

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
<div class="sisrh-pessoa-form">

    <?php
        $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); 
        //Campo de usuário que será tratado diferente em caso de atualização
    ?>

    <?= Tabs::widget([
        'options' => ['class' => 'responsive'],
        'itemOptions' => ['class' => 'responsive'],
        'items' => [
            [
                'label' => Yii::t('app','Personal Data'),
                'active' => !$ocorrencia,
                'content' => "<div class='col-md-12 tabs-conteudo'>

                <div class='col-md-4'>{$form->field($model, 'nome')->textInput(['maxlength' => true])}</div>

                <div class='col-md-4'>{$form->field(isset($model->user) ? $model->user : new User, 'username')->textInput(['readonly' => !Yii::$app->user->can('sisrhAdministrar')])}</div>

                <div class='col-md-4'>{$form->field($model, 'dt_nascimento')->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,])}</div>

                <div class='col-md-4'>{$form->field($model, 'sexo')->radioList(['M' => Yii::t('app','Male'), 'F' => Yii::t('app','Female'), 'I' => Yii::t('app','Intersexual')],['itemOptions'=>['labelOptions' => ['style' => 'margin-right: 10px;']]])}</div>

                <div class='col-md-4'>{$form->field($model, 'estado_civil')->dropDownList($model::ESTADOCIVIL,['prompt' => ''])}</div>
            
                <div class='col-md-4'>{$form->field($model, 'escolaridade')->dropDownList($model::ESCOLARIDADE,['prompt' => ''])}</div>

                <div class='col-md-4'>{$form->field($model, 'emails')->widget(MultipleInput::class, ['max' => 4,])}</div>
            
                <div class='col-md-4'>{$form->field($model, 'telefone')->widget(MultipleInput::class, ['max' => 4,
                    'columns' =>[ ['name' => 'telefone', 'type' => MaskedInput::class, 'options' => ['mask' => '(99) 9999[9]-9999']]]])}</div>

                <div class='col-md-4'>{$form->field($model, 'cpf')->widget(MaskedInput::class, ['mask' => '999.999.999-99',])}</div>
                </div>
                ",
            ],
            [
                'label' => Yii::t('app','Functional Data'),
                'active' => false,
                'content' => "<div class='col-md-12  tabs-conteudo'>
                
                <div class='col-md-4'>{$form->field($model, 'siape')->textInput()}</div>

                <div class='col-md-4'>{$form->field($model, 'regime_trabalho')->dropDownList($model::JORNADA,['prompt' => ''])}</div>
            
                <div class='col-md-4'>{$form->field($model, 'dt_ingresso_orgao')->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,])}</div>

                <div class='col-md-4'>{$form->field($model, 'dt_exercicio')->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,])}</div>
            
                <div class='col-md-4'>{$form->field($model, 'id_cargo')->dropDownList(ArrayHelper::map(SisrhCargo::find()->orderBy('descricao')->all(),'id_cargo','descricao'),['prompt' => ''])}</div>
            
                <div class='col-md-4'>{$form->field($model, 'id_classe_funcional')->dropDownList(ArrayHelper::map(SisrhClasseFuncional::find()->orderBy('id_categoria')->all(),'id_classe_funcional','denominacao'),['prompt' => ''])}</div>
            
                <div class='col-md-4'>{$form->field($model, 'dt_vigencia')->widget(DateControl::class, ['type'=>DateControl::FORMAT_DATE,])}</div>

                <div class='col-md-4'>{$form->field($model, 'id_pessoa_vinculada')->widget(Select2::class, [
                    'data' => ArrayHelper::map(SisrhPessoa::find()->where(['id_cargo' => 1])->orderby('nome')->all(),'id_pessoa','nome'),
                    'options' => ['placeholder' => 'Selecione a pessoa'],
                    'pluginOptions' => ['allowClear' =>true]
                ])}</div>

                <div class='col-md-4'>{$form->field($model, 'situacao')->radioList([1 => Yii::t('app','Ativo'), 0 => Yii::t('app','Inativo')],['itemOptions'=>['labelOptions' => ['style' => 'margin-right: 10px;']]])}</div>
                </div>
                ",
                'visible' => !$model->isNewRecord,
            ],
            [
                'label' => 'Ocorrências',
                'visible' => !$model->isNewRecord,
                'active' => $ocorrencia,
                'content' => "<div class='col-md-12 tabs-conteudo'>" . GridView::widget([
                    'dataProvider' => new ActiveDataProvider(['query' => $model->getAfastamentos(),
                        'sort' => [
                            'defaultOrder' => [
                                'id_afastamento' => SORT_DESC,
                            ]
                        ]]),
                    'pjax' => true,
                
                    'panel' => [
                        'type' => 'primary',
                        'after' => false,
                        'footer' => false,
                    ],
                    'toolbar' =>  [
                        ['content' => 
                        Html::button('<i class="glyphicon glyphicon-plus"></i>',
                            [
                            'value' =>Url::to(['sisrhpessoa/ocorrencia', 'id' => $model->id_pessoa]),
                            'title' => 'Adicionar Ocorrência',
                            'class' => 'btn btn-success modalButton'
                            ]),
                        ],
                
                        '{toggleData}',
                    ],
                    'columns' => [
                        'ocorrencia.justificativa',
                        'inicio:date',
                        'termino:date',
                        'observacao',
            
                        ['class' => 'yii\grid\ActionColumn', 'controller' => 'sisrhafastamento'],
                    ],
                ]) ."</div>"
            ],
        // [
        //     'label' => Yii::t('app','Address'),
        //     'visible' => false, //!$model->isNewRecord, -- retirado em 11/10/19
        //     'content' => "<br />
        //     {$form->field($model, 'id_naturalidade')->widget(Select2::class,
        //         [
        //             'initValueText' => $naturalidade, // set the initial display text
        //             'theme' => Select2::THEME_DEFAULT,
        //             'options' => ['placeholder' => Yii::t('app', 'Search for a city ...')],
        //             'pluginOptions' => [
        //                 'allowClear' => true,
        //                 'minimumInputLength' => 3,
        //                 'language' => [
        //                     'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
        //                 ],
        //                 'ajax' => [
        //                     'url' => $url,
        //                     'dataType' => 'json',
        //                     'data' => new JsExpression('function(params) { return {q:params.term}; }')
        //                 ],
        //                 'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        //                 'templateResult' => new JsExpression('function(city) { return city.text; }'),
        //                 'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        //             ],
        //         ])}

        //     {$form->field($model, 'nacionalidade')->radioList([1 => Yii::t('app','Brasileira'), 0 => Yii::t('app','Estrangeira')],['itemOptions'=>['labelOptions' => ['style' => 'margin-right: 10px;']]])}
        
        //     {$form->field($model, 'endereco')->textInput(['maxlength' => true])}

        //     {$form->field($model, 'numero')->textInput()}
        
        //     {$form->field($model, 'complemento')->textInput(['maxlength' => true])}
        
        //     {$form->field($model, 'bairro')->textInput(['maxlength' => true])}
        
        //     {$form->field($model, 'id_estado')->dropDownList($estados,['prompt' => ''])}
        
        //     {$form->field($model, 'id_municipio')->widget(Select2::class,
        //         [
        //             'initValueText' => $municipio, // set the initial display text
        //             'theme' => Select2::THEME_DEFAULT,
        //             'options' => ['placeholder' => Yii::t('app', 'Search for a city ...')],
        //             'pluginOptions' => [
        //                 'allowClear' => true,
        //                 'minimumInputLength' => 3,
        //                 'language' => [
        //                     'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
        //                 ],
        //                 'ajax' => [
        //                     'url' => $url,
        //                     'dataType' => 'json',
        //                     'data' => new JsExpression('function(params) { return {q:params.term}; }')
        //                 ],
        //                 'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        //                 'templateResult' => new JsExpression('function(city) { return city.text; }'),
        //                 'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        //             ],
        //         ])}
        
        //     {$form->field($model, 'cep')->textInput(['maxlength' => true])}
        //     ",
        // ],
    ],
]);?>

    <div style="background:white; padding:20px; border:1px solid #e2e2e2" class="form-group col-sm-12">
        <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<div class="clearfix"></div>
</div>
<?php
$this->registerJsFile(
    '@web/js/responsive-tabs.js',
    ['depends' => [yii\bootstrap\BootstrapPluginAsset::class]]
);
$this->registerJs(
    "(function($) {
        $('.tab-content').addClass('responsive');
        fakewaffle.responsiveTabs(['xs']);
    })(jQuery);"
);
?>