<?php 

use yii\helpers\Html;
use yii\helpers\URl;
use app\assets\SisligaAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\modules\sisliga\models\SisligaParecer;

SisligaAsset::register($this);

$this->title = 'Gerenciar Ligas Acadêmicas';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisliga'), 'url' => ['/'.strtolower('sisliga')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisliga-default-index">
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
    <div class="sisliga-comissao">
        <?= $this->render('/sisligaligaacademica/_search', ['model' => $searchModel]) ?>

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
                ['attribute' => 'nome', 'format' => 'raw', 'value' => function($data){
                    return Html::a($data->nome,
                        Url::to(['/sisliga/sisligaligaacademica/view', 'id' => $data->id_liga_academica]),
                        ['title' => 'Visualizar','data-toggle'=>'tooltip']);
                }],
                'pessoa.nome',
                 ['attribute' => 'situacao', 'format' => 'raw', 'value' => function($data){
                     return $data->getSituacaoString();}],
                [
                    'label' => 'Parecerista',
                    'format' => 'raw',
                    'value' => function($data){
                        switch($data->situacao){
                            case 1:
                                return Html::button('Definir parecerista',[
                                    'value'=>Url::to(['definirparecerista', 'id' => $data->id_liga_academica]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => 'Definir Parecerista',
                                ]);
                            case 2:
                            case 3:
                            case 4:
                            case 5:
                                $parecer = SisligaParecer::find()->where(['id_liga_academica' => $data->id_liga_academica, 'atual' => 1
                                ])->one();
                                return Html::button($parecer->pessoa->nome,[
                                    'value'=>Url::to(['definirparecerista', 'id' => $data->id_liga_academica]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => 'Alterar Parecerista',
                                ]);
                            case 6:
                                $parecer = SisligaParecer::find()->where(['id_liga_academica' => $data->id_liga_academica, 'atual' => 1
                                ])->one();
                                if($parecer)
                                    return $parecer->pessoa->nome;
                        }
                        return false;
                    }
                ],
                ['label' => 'Data de Aprovação', 'attribute' => 'data_aprovacao_comissao','width' => '10%','format' => 'raw', 'value' => function($data){
                    switch($data->situacao){
                        case 3:
                            return Html::button('Aprovar Liga',[
                                'value'=>Url::to(['aprovarligaacademica', 'id' => $data->id_liga_academica]), //<---- here is where you define the action that handles the ajax request
                                'class'=>'modalButton editModalButton',
                                'data-toggle'=>'tooltip',
                                'title' => "Aprovar Liga Acadêmica",
                            ]);
                        case 6:
                            return Html::button(Yii::$app->formatter->format($data->data_aprovacao_comissao,'date'),[
                                'value'=>Url::to(['aprovarligaacademica', 'id' => $data->id_liga_academica]), //<---- here is where you define the action that handles the ajax request
                                'class'=>'modalButton editModalButton',
                                'data-toggle'=>'tooltip',
                                'title' => "Alterar data de aprovação"
                            ]);
                        case 7:
                            return Yii::$app->formatter->format($data->data_aprovacao_comissao,'date');
                        case 8:
                            return 'Não homologado em: '.Yii::$app->formatter->format($data->data_aprovacao_comissao,'date');
                    }
                    return false;
                }],
                ['label' => 'Congregação', 'attribute' => 'data_homologacao_congregacao','width' => '10%','format' => 'raw', 'value' => function($data){
                    switch($data->situacao){
                        case 6:
                            return Html::button('<span data-toggle="tooltip" title="Inserir dados da sessão da congregacao">Inserir homologação congregação</span>',[
                                'value'=>Url::to(['homologarligaacademica', 'id' => $data->id_liga_academica]), //<---- here is where you define the action that handles the ajax request
                                'class'=>'modalButton editModalButton',
                                //'data-toggle'=>'tooltip',
                                'title' => "Sessão da Congregação",
                            ]);
                        case 7:
                            return Html::button("<span data-toggle='tooltip' title='Editar dados da sessão da congregacao'> $data->sessaoCongregacao ".
                                Yii::$app->formatter->format($data->data_homologacao_congregacao,'date').'</span>',[
                                'value'=>Url::to(['homologarligaacademica', 'id' => $data->id_liga_academica]), //<---- here is where you define the action that handles the ajax request
                                'class'=>'modalButton editModalButton',
                                //'data-toggle'=>'tooltip',
                                'title' => "Sessão da Congregação"
                            ]);
                        case 9:
                            return $data->sessaoCongregacao.' '.Yii::$app->formatter->format(
                                $data->data_homologacao_congregacao,'date');
                    }
                    return false;
                }],
                [
                    'label' => 'Impressão','width' => '12%','format' => 'raw', 'value' => function($data){
                        if($data->situacao < 1)
                            return false;
                        
                        $saida = '<details class="selecionarImpressao">
                                <summary>
                                    <span class="summary-title">Selecionar</span>
                                </summary> <div class="detailsConteudo">';
                        $saida .= Html::a('Liga Acadêmica '.Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['/sisliga/sisligaligaacademica/pdf',
                            'id' => $data->id_liga_academica])).'<br/>';
                        if($data->situacao > 2)
                            $saida .= Html::a('Parecer ' . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['parecer',
                                'id' => $data->id_liga_academica])).'<br/>';
                        return $saida . '</div></details>';
                }],
            ],
        ]); ?>
    </div>
</div>