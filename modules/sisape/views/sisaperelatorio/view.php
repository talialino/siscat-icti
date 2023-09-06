<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\assets\SisapeAsset;
SisapeAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeRelatorio */

$this->title = 'Visualizar Relatório';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = ['label' => 'Meus Relatorios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-relatorio-view">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <?php if(Yii::$app->user->can('sisapeAdministrar')):?>
        <p>
            <?= Html::a('Atualizar', ['update', 'id' => $model->id_relatorio, 'admin' => true], ['class' => 'btn btn-warning pull-right']) ?>
        </p>
    <?php endif;?>
    <div class="clearfix" style="margin-bottom:10px"></div>

    <?= DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => $this->title],
        'enableEditMode' => false,
        'attributes' => [
            'projetoTitulo',
            'situacaoProjeto',
            'justificativa',
            [
                'columns' => [
                    'alunos_orientados',
                    'resumos_publicados',
                ]
            ],
            [
                'columns' => [
                    'artigos_publicados',
                    'artigos_aceitos',
                ]
            ],
            [
                'columns' => [
                    'relatorio_agencia:boolean',
                    'deposito_patente:boolean',
                ]
            ],
            'outros_indicadores:ntext',
            'consideracoes_finais:ntext',
            [
                'columns' => [
                    'data_relatorio:date',
                    'data_aprovacao_nucleo:date',
                ]
            ],
            [
                'columns' => [
                    'data_aprovacao_copex:date',
                    'data_homologacao_congregacao:date',
                ]
            ], 
            [
                'columns' => [
                    'sessao_congregacao',
                    'tipo_sessao_congregacao',
                ]
            ],                           
            'situacaoString',
        ],
    ]) ?>

    <?=Html::a('Salvar em PDF', ['pdf', 'id' => $model->id_relatorio],
                ['class' => 'btn btn-primary']
        )?>

</div>
