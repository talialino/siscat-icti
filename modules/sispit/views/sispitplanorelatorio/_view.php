<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\detail\DetailView;
use app\modules\sispit\models\ResumoCargaHoraria;
use app\assets\SispitAsset;
use app\modules\sispit\models\SispitPlanoRelatorioSuplementar;

SispitAsset::register($this);

$suplementar = Yii::$app->session->get('siscat_ano')->suplementar || ($pit_rit && $model->ehRitParcial());
if(($pit_rit && $model->ehRitParcial()) && !($model instanceof SispitPlanoRelatorioSuplementar))
    $model = SispitPlanoRelatorioSuplementar::findOne($model->id_plano_relatorio);
$resumoCH = $model->getTotal($pit_rit);
$dataProvider = ResumoCargaHoraria::fromArray($resumoCH);
?>
<div class="sispit-plano-relatorio-view">
<?php
echo DetailView::widget([
    'model' => $model->pessoa,
    'panel' => [
        'type' => 'default', 
        'heading' => 'Informações do Professor', 
        'before' => false,
        'after' => false
    ],
    
    'enableEditMode' => false,
    'attributes' => [
        [
            'columns' => [
                ['attribute' =>'nome', 'labelColOptions' => ['style' => 'width: 10%']],
                ['attribute' =>'siape', 'labelColOptions' => ['style' => 'width: 10%']],
                ['attribute' =>'jornada', 'labelColOptions' => ['style' => 'width: 15%']],
            ],
        ]
    ]
]);
?>
<div class="infoPit">
<h3 class="titulo">INFORMAÇÕES DO <?=$pit_rit ? 'RIT' : 'PIT'?></h3>
<?
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'striped' =>false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Resumo da Carga Horária Semanal',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        'descricao',
        ['attribute' => 'semestre1', 'label' => $suplementar ? 'Carga Horária' : '1º Semestre'],
        ['attribute' => 'semestre2', 'visible' => !$suplementar]
    ],
]);
echo GridView::widget([
    'dataProvider' => $ensinoComponente->search(array()),
    'striped' =>false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Componentes',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        ['attribute' => 'semestre', 'group' => true, 'visible' => !$suplementar],
        'nivelGraduacao',
        'componenteCurricular.codigoNome',
        'ch_teorica',
        'ch_pratica',
        'ch_estagio',
        'total',
    ],
]);
echo DetailView::widget([
    'model' => $model->sispitAtividadeComplementar,
    'panel' => ['type' => 'default', 'heading' => 'Atividade Complementar', 'before' => false,
    'after' => false],
    'enableEditMode' => false,
    'attributes' => [
        [
            'columns' => [
                ['attribute' => $pit_rit ? 'ch_graduacao_sem1_rit' : 'ch_graduacao_sem1_pit', 'label' => $suplementar ? 'CH Graduação' : null],
                ['attribute' => $pit_rit ? 'ch_pos_sem1_rit' : 'ch_pos_sem1_pit', 'label' => $suplementar ? 'CH Pós-graduação' : null],

                ['attribute' => $pit_rit ? 'ch_graduacao_sem2_rit' : 'ch_graduacao_sem2_pit', 'visible' => !$suplementar],
                ['attribute' => $pit_rit ? 'ch_pos_sem2_rit' : 'ch_pos_sem2_pit', 'visible' => !$suplementar],
            ]
        ]
    ]
]);
echo GridView::widget([
    'dataProvider' => $orientacaoAcademica->search(array()),
    'striped' => false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Orientação Acadêmica',
        'footer' => 
        "<span style='width: 50%; display: inline-block;'><strong>Carga Horária ".(!$suplementar ? "1º semestre" : '').":</strong> {$resumoCH['Orientação Acadêmica'][0]['total']}</span>".
            (!$suplementar ? "<span><strong>Carga Horária 2º semestre:</strong> {$resumoCH['Orientação Acadêmica'][1]['total']}</span>" : '')
        ,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        ['attribute' => 'semestre', 'width' =>'15%', 'group' => true, 'visible' => !$suplementar],
        'aluno.nome',
    ],
]);
echo GridView::widget([
    'dataProvider' => $monitoria->search(array()),
    'striped' =>false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Monitoria',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' => [],
    'columns' => [
        ['attribute' => 'semestre', 'group' => true, 'visible' => !$suplementar],
        'discente',
        'componenteCurricular.nome',
        'carga_horaria',
    ],
]);
echo GridView::widget([
    'dataProvider' => $ensinoOrientacao->search(array()),
    'striped' =>false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Outras Orientações',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        ['attribute' => 'semestre', 'group' => true, 'visible' => !$suplementar],
        ['attribute' => 'tipoOrientacao', 'group' => true],
        'discente',
        'projeto:text',
        'carga_horaria',
    ],
]);
echo GridView::widget([
    'dataProvider' => $ligaAcademica->search(array()),
    //'pjax' => true,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Liga Acadêmica',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        ['attribute' => 'semestre', 'visible' => !$suplementar],
        'ligaAcademica.nome',
        'funcaoString',
        'carga_horaria',
    ],
]);
echo GridView::widget([
    'dataProvider' => $pesquisaExtensao->search(array()),
    //'pjax' => true,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Pesquisa e Extensão',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        ['attribute' => 'semestre', 'visible' => !$suplementar],
        ['attribute' => 'tipo', 'group' => true],
        'projeto.titulo',
        'tipoParticipacao',
        'carga_horaria',
    ],
]);
echo GridView::widget([
    'dataProvider' => $atividadesAdministrativas->search(array()),
    'striped' => false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Atividades Administrativas',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [  ],
    'columns' => [
        [
            'attribute' => 'semestre', 
            'width' => '10%',
            'group' => true,  // enable grouping
            'visible' => !$suplementar
        ],
        [
            'attribute' => 'tipoAtividade',
            'width' => '30%',
        ],
        ['attribute' => 'descricao', 'width' => '40%'],
        ['attribute' => 'carga_horaria', 'width' => '10%'],

    ],
]);
echo GridView::widget([
    'dataProvider' => $afastamentoDocente->search(array()),
    'striped' => false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Afastamento',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        ['attribute' => 'semestre', 'group' => true, 'visible' => !$suplementar],
        ['attribute' =>'descricao','width' => '50%'],
        'nivelGraduacao',
        'carga_horaria',
        'data_inicio:date',
        'data_fim:date',
    ],
]);
echo GridView::widget([
    'dataProvider' => $outrasOcorrencias->search(array()),
    'striped' => false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Outras Ocorrências',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        ['attribute' => 'semestre', 'group' => true, 'visible' => !$suplementar],
        ['attribute' =>'descricao','width' => '50%'],
        'nivelGraduacao',
        'carga_horaria',
        'data_inicio:date',
        'data_fim:date',
    ],
]);
echo GridView::widget([
    'dataProvider' => $participacaoEventos->search(array()),
    'striped' => false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Participação em Eventos',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [ ],
    'columns' => [
        ['attribute' => 'semestre', 'group' => true, 'visible' => !$suplementar],
        'nome:text',
        'tipoEvento',
        'tipoParticipacao',
        'local',
        'data_inicio:date',
        'data_fim:date',
    ],
]);
echo GridView::widget([
    'dataProvider' => $publicacao->search(array()),
    'striped' => false,
    'hover' => true,
    'responsiveWrap' => false,
    'panel' => [
        'type' => 'default',
        'heading' => 'Publicações',
        'footer' => false,
        'before' => false,
        'after' => false
    ],
    'toolbar' =>  [],
    'columns' => [
        ['attribute' => 'semestre', 'group' => true, 'visible' => !$suplementar],
        ['attribute' => 'titulo',],
        ['attribute' => 'editora'],
        'local',
        'fonte_financiadora',
    ],
]);
echo DetailView::widget([
    'model' => $model,
    'panel' => ['type' => 'default', 'heading' => 'Observações', 'before' => false, 'after' => false],
    'enableEditMode' => false,
    'attributes' => [
        [
            'columns' => [
                ['attribute' => $pit_rit ? 'observacao_rit' : 'observacao_pit', 'labelColOptions' => ['style'=>'display: none;']]
            ]
        ]
    ]
]);
?>
</div>
</div>