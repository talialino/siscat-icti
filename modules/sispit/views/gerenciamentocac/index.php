<?php 

use yii\helpers\Html;
use yii\helpers\URl;
use app\assets\SispitAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\modules\sispit\models\SispitPlanoRelatorio;
use app\modules\sispit\models\SispitParecer;

SispitAsset::register($this);

$this->title = 'Gerenciamento Coordenação Acadêmica';
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
    <div class="sispit-cac-docentes">

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
                    'value' => function($model){
                        $plano = $model->sispitPlanoRelatorio;
                        if($plano == null)
                            return $model->nome;
                        return Html::a($model->nome,
                            Url::to(['view', 'id' => $plano->id_plano_relatorio]),
                            ['title' => 'Visualizar','data-toggle'=>'tooltip','data-placement'=>'bottom']);
                    }
                ],
                 ['label' => 'Núcleo', 'value' => function($model){
                     $nucleo = $model->getSisrhSetores()->where(['eh_nucleo_academico' => 1])->one();
                     if($nucleo == null)
                        return false;
                    return $nucleo->sigla;
                 }],
                ['label' => 'Situação', 'format' => 'raw', 'value' => function($model){
                    $plano = $model->sispitPlanoRelatorio;
                    if($plano == null)
                        return "PIT não criado";
                    $status = $plano->status;
                    if($status > 0 && $status < 15)
                        return Html::button($plano->situacao,[
                            'value'=>Url::to(['definirsituacao', 'id' => $plano->id_plano_relatorio]), //<---- here is where you define the action that handles the ajax request
                            'class'=>'modalButton editModalButton',
                            'data-toggle'=>'tooltip',
                            'title' => (($status % 10 > 0 && $status % 10 < 5)) ?
                                'Avaliar diretamente pela CAC' : 'Permissão de envio de RIT PARCIAL',
                        ]);
                    if($plano->status == 19 && $plano->situacao_estagio_probatorio == 1 && $plano->sispitAno->suplementar == 0)
                        return $plano->situacao.Html::a(' (Liberar RIT Final)',
                            Url::to(['preencherritfinal', 'id' => $plano->id_plano_relatorio]),
                            ['title' => 'Permitir preenchimento do RIT Final','data-toggle'=>'tooltip','data-placement'=>'bottom']);
                    
                    return $plano->situacao;
                }],
                [
                    'label' => 'Parecerista',
                    'format' => 'raw',
                    'value' => function($model){
                        $plano = $model->sispitPlanoRelatorio;
                        if($plano){
                            switch($plano->status % 10){
                                case 5:
                                    return Html::button('Definir parecerista',[
                                        'value'=>Url::to(['definirparecerista', 'id' => $plano->id_plano_relatorio]), //<---- here is where you define the action that handles the ajax request
                                        'class'=>'modalButton editModalButton',
                                        'data-toggle'=>'tooltip',
                                        'title' => 'Definir Parecerista',
                                    ]);
                                case 6:
                                case 7:
                                case 8:
                                    $parecer = SispitParecer::find()->where(['id_plano_relatorio' => $plano->id_plano_relatorio,
                                        'tipo_parecerista' => SispitParecer::PARECERISTA_CAC, 'atual' => 1, 'pit_rit' => ($plano->status > 9 ? 1 : 0)
                                    ])->one();
                                    return Html::button($parecer->pessoa->nome,[
                                        'value'=>Url::to(['definirparecerista', 'id' => $plano->id_plano_relatorio]), //<---- here is where you define the action that handles the ajax request
                                        'class'=>'modalButton editModalButton',
                                        'data-toggle'=>'tooltip',
                                        'title' => 'Alterar Parecerista',
                                    ]);
                                case 9:
                                    $parecer = SispitParecer::find()->where(['id_plano_relatorio' => $plano->id_plano_relatorio,
                                    'tipo_parecerista' => SispitParecer::PARECERISTA_CAC,'atual' => 1, 'pit_rit' => ($plano->status > 9 ? 1 : 0)
                                    ])->one();
                                    return $parecer->pessoa->nome;
                            }
                        }
                        return false;
                    }
                ],
                ['label' => 'Data de Aprovação','width' => '15%','format' => 'raw', 'value' => function($model){
                    $plano = $model->sispitPlanoRelatorio;
                    if($plano){
                        switch($plano->status){
                            case 7:
                                return Html::button('Aprovar PIT',[
                                    'value'=>Url::to(['aprovarplanorelatorio', 'id' => $plano->id_plano_relatorio]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => "Aprovar PIT de $model->nome",
                                ]);
                            case 9:
                                return Html::button('PIT: '.Yii::$app->formatter->format($plano->data_homologacao_cac_pit,'date'),[
                                    'value'=>Url::to(['aprovarplanorelatorio', 'id' => $plano->id_plano_relatorio]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => "Alterar data de aprovação PIT de $model->nome"
                                ]);
                            case 10:
                            case 11:
                            case 12:
                            case 13:
                            case 14:
                            case 15:
                            case 16:
                            case 18:
                                return 'PIT: '.Yii::$app->formatter->format($plano->data_homologacao_cac_pit,'date');
                            case 17:
                                return 'PIT: '.Yii::$app->formatter->format($plano->data_homologacao_cac_pit,'date').'<br />'.
                                    Html::button('Aprovar RIT',[
                                    'value'=>Url::to(['aprovarplanorelatorio', 'id' => $plano->id_plano_relatorio]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => "Aprovar RIT de $model->nome",
                                ]);
                            case 19:
                                return 'PIT: '.Yii::$app->formatter->format($plano->data_homologacao_cac_pit,'date').'<br />'.
                                    Html::button('RIT: '.Yii::$app->formatter->format($plano->data_homologacao_cac_rit,'date'),[
                                    'value'=>Url::to(['aprovarplanorelatorio', 'id' => $plano->id_plano_relatorio]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => "Alterar data de aprovação RIT de $model->nome"
                                ]);
                        }
                    }
                    return false;
                }],
                [
                    'label' => 'Impressão','width' => '18%','format' => 'raw', 'value' => function($model){
                        $plano = $model->sispitPlanoRelatorio;
                        if($plano == null || $plano->status <= 6)
                            return false;
                        $saida = '<details class="selecionarImpressao">
                                <summary>
                                    <span class="summary-title">Selecionar Impressão</span>
                                </summary> <div class="detailsConteudo">';
                        if($plano->status >= 9)
                            $saida .= Html::a('PIT ' . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['/sispit/sispitplanorelatorio/pdf',
                                'id' => $plano->id_plano_relatorio, 'pit_rit' => 0])).'<br/>';
                        if($plano->status > 6)
                            $saida .= Html::a('Parecer PIT ' . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['parecer',
                                'id' => $plano->id_plano_relatorio, 'pit_rit' => 0])).'<br/>';
                        if($plano->status == 19)
                            $saida .= Html::a('RIT '  . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['/sispit/sispitplanorelatorio/pdf',
                                'id' => $plano->id_plano_relatorio, 'pit_rit' => 1])).'<br/>';
                        if($plano->status > 16)
                            $saida .= Html::a('Parecer RIT ' . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['parecer',
                                'id' => $plano->id_plano_relatorio, 'pit_rit' => 1]));
                        return $saida . '</div></details>';
                }],
            ],
        ]); ?>
    </div>
</div>