<?php 

use yii\helpers\Html;
use yii\helpers\URl;
use app\assets\SisapeAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use app\modules\sisape\models\SisapeParecer;

SisapeAsset::register($this);

$this->title = 'Gerenciar Projetos - Núcleo';
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
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>
    <div class="sisape-gerenciar-nucleo">
        <?= $this->render('/sisapeprojeto/_search', ['model' => $searchModel]) ?>

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
                        Url::to(['/sisape/sisapeprojeto/view', 'id' => $data->id_projeto]),
                        ['title' => 'Visualizar','data-toggle'=>'tooltip']);
                }],
                'pessoa.nome',
                 ['attribute' => 'situacao', 'format' => 'raw', 'value' => function($data){
                     return $data->getSituacaoString();}],
                /*[
                    'label' => 'Parecerista',
                    'format' => 'raw',
                    'value' => function($data){
                        switch($data->situacao){
                            case 0:
                                return false;
                            case 1:
                                return Html::button('Definir parecerista',[
                                    'value'=>Url::to(['definirparecerista', 'id' => $data->id_projeto]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => 'Definir Parecerista',
                                ]);
                            case 2:
                            case 3:
                            case 4:
                            case 5:
                                $parecer = SisapeParecer::find()->where(['id_projeto' => $data->id_projeto,
                                    'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,'atual' => 1
                                ])->one();
                                return Html::button($parecer->pessoa->nome,[
                                    'value'=>Url::to(['definirparecerista', 'id' => $data->id_projeto]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => 'Alterar Parecerista',
                                ]);
                            default:
                                $parecer = SisapeParecer::find()->where(['id_projeto' => $data->id_projeto,
                                    'tipo_parecerista' => SisapeParecer::PARECERISTA_NUCLEO,'atual' => 1
                                ])->one();
                                if($parecer)
                                    return $parecer->pessoa->nome;
                        }
                        return false;
                    }
                ],
                ['label' => 'Data de Aprovação', 'attribute' => 'data_aprovacao_nucleo','width' => '15%','format' => 'raw', 'value' => function($data){
                    if($data->id_projeto){
                        switch($data->situacao){
                            case 3:
                                return Html::button('Aprovar Projeto',[
                                    'value'=>Url::to(['aprovarprojeto', 'id' => $data->id_projeto]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => "Aprovar Projeto",
                                ]);
                            case 6:
                                return Html::button(Yii::$app->formatter->format($data->data_aprovacao_nucleo,'date'),[
                                    'value'=>Url::to(['aprovarprojeto', 'id' => $data->id_projeto]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => "Alterar data de aprovação"
                                ]);
                            case 7:
                            case 8:
                            case 9:
                            case 10:
                            case 11:
                            case 12:
                            case 14:
                                return Yii::$app->formatter->format($data->data_aprovacao_nucleo,'date');
                        }
                    }
                    return false;
                }],*/
                [
                    'label' => ''/*'Impressão','width' => '18%'*/,'format' => 'raw', 'value' => function($data){
                        if($data->situacao < 1)
                            return false;
                        return Html::a(Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['/sisape/sisapeprojeto/pdf',
                            'id' => $data->id_projeto]),['title' => 'Imprimir projeto']);
                        $saida = '<details class="selecionarImpressao">
                                <summary>
                                    <span class="summary-title">Selecionar Impressão</span>
                                </summary> <div class="detailsConteudo">';
                        $saida .= Html::a('Projeto '.Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['/sisape/sisapeprojeto/pdf',
                            'id' => $data->id_projeto])).'<br/>';
                        if($data->situacao > 2)
                            $saida .= Html::a('Parecer ' . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['parecer',
                                'id' => $data->id_projeto])).'<br/>';
                        return $saida . '</div></details>';
                }],
            ],
        ]); ?>
    </div>
</div>