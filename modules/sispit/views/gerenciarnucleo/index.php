<?php 

use yii\helpers\Html;
use yii\helpers\URl;
use app\assets\SispitAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\modules\sispit\models\SispitPlanoRelatorio;
use app\modules\sispit\models\SispitParecer;

SispitAsset::register($this);

$this->title = 'Gerenciar Núcleo Acadêmico';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
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
    <div class="sispit-nucleo-docentes">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsiveWrap' => false,
            'hover' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => 'Docentes',
                'footer' => false,
            ],
            'toolbar' =>  false,
            'columns' => [
                ['attribute' => 'nome', 'format' => 'raw',
                    'value' => function($data){
                        if($data['status'] === null)
                            return $data['nome'];
                        return Html::a($data['nome'],
                            Url::to(['view', 'id' => $data['id_plano_relatorio']]),
                            ['title' => 'Visualizar','data-toggle'=>'tooltip']);
                    }
                ],
                ['attribute' => 'status', 'label' => 'Situação', 'value' => function($data){
                    if($data['status'] === null)
                        return "PIT não criado";
                    return SispitPlanoRelatorio::getSituacaoPlanoRelatorio($data['id_plano_relatorio']);
                }],
                [
                    'label' => 'Parecerista',
                    'format' => 'raw',
                    'value' => function($data){
                        if($data['id_plano_relatorio']){
                            switch($data['status'] % 10){
                                case 0:
                                    return false;
                                case 1:
                                    return Html::button('Definir parecerista',[
                                        'value'=>Url::to(['definirparecerista', 'id' => $data['id_plano_relatorio']]), //<---- here is where you define the action that handles the ajax request
                                        'class'=>'modalButton editModalButton',
                                        'data-toggle'=>'tooltip',
                                        'title' => 'Definir Parecerista',
                                    ]);
                                case 2:
                                case 3:
                                case 4:
                                    $parecer = SispitParecer::find()->where(['id_plano_relatorio' => $data['id_plano_relatorio'],
                                        'tipo_parecerista' => SispitParecer::PARECERISTA_NUCLEO,'atual' => 1, 'pit_rit' => ($data['status'] > 9 ? 1 : 0)
                                    ])->one();
                                    if($parecer)
                                        return Html::button($parecer->pessoa->nome,[
                                            'value'=>Url::to(['definirparecerista', 'id' => $data['id_plano_relatorio']]), //<---- here is where you define the action that handles the ajax request
                                            'class'=>'modalButton editModalButton',
                                            'data-toggle'=>'tooltip',
                                            'title' => 'Alterar Parecerista',
                                        ]);
                                default:
                                    $parecer = SispitParecer::find()->where(['id_plano_relatorio' => $data['id_plano_relatorio'],
                                        'tipo_parecerista' => SispitParecer::PARECERISTA_NUCLEO,'atual' => 1, 'pit_rit' => ($data['status'] > 9 ? 1 : 0)
                                    ])->one();
                                    if($parecer)
                                        return $parecer->pessoa->nome;
                            }
                        }
                        return false;
                    }
                ],
                ['label' => 'Data de Aprovação','width' => '15%','format' => 'raw', 'value' => function($data){
                    if($data['id_plano_relatorio']){
                        switch($data['status']){
                            case 3:
                                return Html::button('Aprovar PIT',[
                                    'value'=>Url::to(['aprovarplanorelatorio', 'id' => $data['id_plano_relatorio']]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => "Aprovar PIT de {$data['nome']}",
                                ]);
                            case 5:
                                if($data['data_homologacao_nucleo_pit']) 
                                    return Html::button('PIT: '.Yii::$app->formatter->format($data['data_homologacao_nucleo_pit'],'date'),[
                                        'value'=>Url::to(['aprovarplanorelatorio', 'id' => $data['id_plano_relatorio']]), //<---- here is where you define the action that handles the ajax request
                                        'class'=>'modalButton editModalButton',
                                        'data-toggle'=>'tooltip',
                                        'title' => "Alterar data de aprovação PIT de {$data['nome']}"
                                    ]);
                                else
                                    break;
                            case 6:
                            case 7:
                            case 8:
                            case 9:
                            case 10:
                            case 11:
                            case 12:
                            case 14:
                                return 'PIT: '.Yii::$app->formatter->format($data['data_homologacao_nucleo_pit'],'date');
                            case 13:
                                return 'PIT: '.Yii::$app->formatter->format($data['data_homologacao_nucleo_pit'],'date').'<br />'.
                                    Html::button('Aprovar RIT',[
                                    'value'=>Url::to(['aprovarplanorelatorio', 'id' => $data['id_plano_relatorio']]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => "Aprovar RIT de {$data['nome']}",
                                ]);
                            case 15:
                                if($data['data_homologacao_nucleo_rit'])
                                    return 'PIT: '.Yii::$app->formatter->format($data['data_homologacao_nucleo_pit'],'date').'<br />'.
                                        Html::button('RIT: '.Yii::$app->formatter->format($data['data_homologacao_nucleo_rit'],'date'),[
                                        'value'=>Url::to(['aprovarplanorelatorio', 'id' => $data['id_plano_relatorio']]), //<---- here is where you define the action that handles the ajax request
                                        'class'=>'modalButton editModalButton',
                                        'data-toggle'=>'tooltip',
                                        'title' => "Alterar data de aprovação RIT de {$data['nome']}"
                                ]);
                            case 16:
                            case 17:
                            case 18:
                            case 19:
                                return 'PIT: '.Yii::$app->formatter->format($data['data_homologacao_nucleo_pit'],'date').'<br />'.
                                    'RIT: '.Yii::$app->formatter->format($data['data_homologacao_nucleo_rit'],'date');
                        }
                    }
                    return false;
                }],
                [
                    'label' => 'Impressão','width' => '18%','format' => 'raw', 'value' => function($data){
                        if($data['status'] === null || $data['status'] <= 2)
                            return false;
                        $saida = '<details class="selecionarImpressao">
                                <summary>
                                    <span class="summary-title">Selecionar Impressão</span>
                                </summary> <div class="detailsConteudo">';
                        if($data['status'] >= 9)
                            $saida .= Html::a('PIT ' . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['/sispit/sispitplanorelatorio/pdf',
                                'id' => $data['id_plano_relatorio'], 'pit_rit' => 0])).'<br/>';
                        if($data['status'] > 2)
                            $saida .= Html::a('Parecer PIT ' . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['parecer',
                                'id' => $data['id_plano_relatorio'], 'pit_rit' => 0])).'<br/>';
                        if($data['status'] == 19)
                            $saida .= Html::a('RIT '  . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['/sispit/sispitplanorelatorio/pdf',
                                'id' => $data['id_plano_relatorio'], 'pit_rit' => 1])).'<br/>';
                        if($data['status'] > 12)
                            $saida .= Html::a('Parecer RIT ' . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['parecer',
                                'id' => $data['id_plano_relatorio'], 'pit_rit' => 1]));
                        return $saida . '</div></details>';
                }],
            ],
        ]); ?>
    </div>
</div>