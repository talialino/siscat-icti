<?php 

use yii\helpers\Html;
use yii\helpers\URl;
use app\assets\SisapeAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use app\modules\sisape\models\SisapeParecer;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\sisape\models\SisapeProjeto;
use app\modules\sisrh\models\SisrhPessoa;
use kartik\select2\Select2;

SisapeAsset::register($this);

$this->title = 'Gerenciar Relatórios - Núcleo';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisape'), 'url' => ['/'.strtolower('sisape')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-default-index">
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
    <div class="sisape-gerenciar-nucleo">
        <div class="sisape-projeto-search">

            <?php $form = ActiveForm::begin([
                'method' => 'get',
            ]); ?>
            <?= $form->field($searchModel, 'id_projeto',['options' => ['class' => 'col-md-12']])->widget(Select2::class, [
                    'data' => ArrayHelper::map(SisapeProjeto::find()->select(['id_projeto','titulo'])->orderby('titulo')->all(),'id_projeto','titulo'),
                    'options' => ['placeholder' => 'Selecione o projeto'],
                    'pluginOptions' => ['allowClear' =>true]
            ]) ?>
            <?= $form->field($searchModel, 'id_pessoa',['options' => ['class' => 'col-md-4']])->label('Coordenador(a)')->widget(Select2::class, [
                    'data' => ArrayHelper::map(SisrhPessoa::find()->orderby('nome')->all(),'id_pessoa','nome'),
                    'options' => ['placeholder' => 'Selecione a pessoa'],
                    'pluginOptions' => ['allowClear' =>true]
            ]) ?>
            <?= $form->field($searchModel, 'tipo_projeto',['options' => ['class' => 'col-md-2']])->label('Tipo de Projeto')->dropDownList(SisapeProjeto::TIPO_PROJETO, ['prompt' => '']) ?>

            <?= $form->field($searchModel, 'situacao',['options' => ['class' => 'col-md-6']])->label('Situação do Relatório')->dropDownList($searchModel::SITUACAO, ['prompt' => '']) ?>

            <div class="form-group col-md-3" style="margin-top:14px">
                <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
            </div>
            <div class="clearfix"></div>


            <?php ActiveForm::end(); ?>

        </div>

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
                ['attribute' => 'titulo', 'format' => 'raw', 'value' => function($data){
                    return Html::a($data->titulo,
                        Url::to(['/sisape/sisaperelatorio/view', 'id' => $data->id_relatorio]),
                        ['title' => 'Visualizar','data-toggle'=>'tooltip']);
                }],
                'pessoa.nome',
                 ['attribute' => 'situacao', 'format' => 'raw', 'value' => function($data){
                     return $data->getSituacaoString();}],
               /* [
                    'label' => 'Parecerista',
                    'format' => 'raw',
                    'value' => function($data){
                        switch($data->situacao){
                            case 0:
                                return false;
                            case 1:
                                return Html::button('Definir parecerista',[
                                    'value'=>Url::to(['definirpareceristarelatorio', 'id' => $data->id_relatorio]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => 'Definir Parecerista',
                                ]);
                            case 2:
                            case 3:
                            case 4:
                            case 5:
                                $parecer = SisapeParecer::find()->where(['id_relatorio' => $data->id_relatorio,
                                    'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,'atual' => 1
                                ])->one();
                                return Html::button($parecer->pessoa->nome,[
                                    'value'=>Url::to(['definirpareceristarelatorio', 'id' => $data->id_relatorio]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => 'Alterar Parecerista',
                                ]);
                            default:
                                $parecer = SisapeParecer::find()->where(['id_relatorio' => $data->id_relatorio,
                                    'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,'atual' => 1
                                ])->one();
                                if($parecer)
                                    return $parecer->pessoa->nome;
                        }
                        return false;
                    }
                ],
                ['label' => 'Data de Aprovação', 'attribute' => 'data_aprovacao_nucleo','width' => '15%','format' => 'raw', 'value' => function($data){
                    if($data->id_relatorio){
                        switch($data->situacao){
                            case 3:
                                return Html::button('Aprovar Relatório',[
                                    'value'=>Url::to(['aprovarrelatorio', 'id' => $data->id_relatorio]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => "Aprovar relatorio",
                                ]);
                            case 6:
                                return Html::button(Yii::$app->formatter->format($data->data_aprovacao_nucleo,'date'),[
                                    'value'=>Url::to(['aprovarrelatorio', 'id' => $data->id_relatorio]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => "Alterar data de aprovação"
                                ]);
                            case 7:
                            case 8:
                            case 9:
                            case 10:
                            case 11:
                                return Yii::$app->formatter->format($data->data_aprovacao_nucleo,'date');
                        }
                    }
                    return false;
                }],*/
                [
                    'label' => ''/*'Impressão','width' => '18%'*/,'format' => 'raw', 'value' => function($data){
                        return Html::a(Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['/sisape/sisaperelatorio/pdf',
                        'id' => $data->id_relatorio]));
                        /*$saida = '<details class="selecionarImpressao">
                                <summary>
                                    <span class="summary-title">Selecionar Impressão</span>
                                </summary> <div class="detailsConteudo">';
                        $saida .= Html::a('relatorio '.Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['/sisape/sisaperelatorio/pdf',
                            'id' => $data->id_relatorio])).'<br/>';
                        if($data->situacao > 2)
                            $saida .= Html::a('Parecer ' . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['parecerrelatorio',
                                'id' => $data->id_relatorio])).'<br/>';
                        return $saida . '</div></details>';*/
                }],
            ],
        ]); ?>
    </div>
</div>