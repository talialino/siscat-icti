<?php 

use yii\helpers\Html;
use yii\helpers\URl;
use app\assets\SisccAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use app\modules\siscc\models\SisccParecer;

SisccAsset::register($this);

$this->title = 'Gerenciar Coordenação';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
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
    <div class="siscc-cac">
        <?= $this->render('/sisccprogramacomponentecurricular/_search', ['model' => $searchModel]) ?>

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
                ['attribute' => 'componente', 'format' => 'raw', 'value' => function($data){
                    return Html::a($data->componente,
                        Url::to(['/siscc/sisccprogramacomponentecurricular/view', 'id' => $data->id_programa_componente_curricular]),
                        ['title' => 'Visualizar','data-toggle'=>'tooltip']);
                }],
                'colegiado',
                ['label' => 'Docentes','width' => '20%','value' => function($data){return GridView::widget([
                    'dataProvider' => new ActiveDataProvider(['query' => $data->getSisccProgramaComponenteCurricularPessoas()]),
                    'summary' => '',
                    'responsiveWrap' => false,
                    'showHeader'=> false,
                    'columns' => [
                        'pessoa.nome',
                    ],
                ]);}, 'format' => 'raw'],
                 ['attribute' => 'situacao', 'format' => 'raw', 'value' => function($data){
                     return $data->getSituacaoString();}],
                [
                    'label' => 'Parecerista',
                    'format' => 'raw',
                    'value' => function($data){
                        switch($data->situacao){
                            case 6:
                                return Html::button('Definir parecerista',[
                                    'value'=>Url::to(['definirparecerista', 'id' => $data->id_programa_componente_curricular]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => 'Definir Parecerista',
                                ]);
                            case 7:
                            case 8:
                            case 9:
                            case 10:
                                $parecer = SisccParecer::find()->where(['id_programa_componente_curricular' => $data->id_programa_componente_curricular,
                                    'tipo_parecerista' => SisccParecer::PARECERISTA_CAC,'atual' => 1
                                ])->one();
                                return Html::button($parecer->pessoa->nome,[
                                    'value'=>Url::to(['definirparecerista', 'id' => $data->id_programa_componente_curricular]), //<---- here is where you define the action that handles the ajax request
                                    'class'=>'modalButton editModalButton',
                                    'data-toggle'=>'tooltip',
                                    'title' => 'Alterar Parecerista',
                                ]);
                            case 11:
                                $parecer = SisccParecer::find()->where(['id_programa_componente_curricular' => $data->id_programa_componente_curricular,
                                    'tipo_parecerista' => SisccParecer::PARECERISTA_CAC,'atual' => 1
                                ])->one();
                                if($parecer)
                                    return $parecer->pessoa->nome;
                        }
                        return false;
                    }
                ],
                ['label' => 'Data de Aprovação', 'attribute' => 'data_aprovacao_coordenacao','width' => '10%','format' => 'raw', 'value' => function($data){
                    switch($data->situacao){
                        case 8:
                            return Html::button('Aprovar Programa',[
                                'value'=>Url::to(['aprovarprograma', 'id' => $data->id_programa_componente_curricular]), //<---- here is where you define the action that handles the ajax request
                                'class'=>'modalButton editModalButton',
                                'data-toggle'=>'tooltip',
                                'title' => "Aprovar Programa",
                            ]);
                        case 11:
                            return Html::button(Yii::$app->formatter->format($data->data_aprovacao_coordenacao,'date'),[
                                'value'=>Url::to(['aprovarprograma', 'id' => $data->id_programa_componente_curricular]), //<---- here is where you define the action that handles the ajax request
                                'class'=>'modalButton editModalButton',
                                'data-toggle'=>'tooltip',
                                'title' => "Alterar data de aprovação"
                            ]);
                    }
                    return false;
                }],
                [
                    'label' => 'Impressão','width' => '18%','format' => 'raw', 'value' => function($data){
                        if($data->situacao == 0)
                            return false;
                        $saida = '<details class="selecionarImpressao">
                                <summary>
                                    <span class="summary-title">Selecionar Impressão</span>
                                </summary> <div class="detailsConteudo">';
                        if($data->situacao > 0)
                            $saida .= Html::a('Programa '.Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['/siscc/sisccprogramacomponentecurricular/pdf',
                                'id' => $data->id_programa_componente_curricular])).'<br/>';
                        if($data->situacao > 7)
                            $saida .= Html::a('Parecer ' . Html::tag('i', '', ['class' => 'fa fa-file-pdf-o']), Url::to(['parecer',
                                'id' => $data->id_programa_componente_curricular])).'<br/>';
                        return $saida . '</div></details>';
                }],
            ],
        ]); ?>
    </div>
</div>