<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

SisaiAsset::register($this);


/* @var $this yii\web\View */
/* @var $avaliacao app\modules\sisai\models\SisaiAvaliacao */

$this->title = 'Avaliação Concluída';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-avaliacao-final">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    
        <div class="sisai-final-discente box box-info">
            <div class="mensagem-sucesso">
                <p>Obrigada por sua avaliação!</p>
                <p>Sua contribuição é muito importante para o processo avaliativo do nosso instituto, o que nos permite estar sempre aperfeiçoando nossos serviços.</p>
            </div>
            <?= GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $avaliacao->getSisaiAlunoComponenteCurriculares()]),
                'panel' => ['type' => 'primary', 'heading' => 'Componentes Avaliados'],
                'toolbar' =>  [['content' => false,],],
                'summary' => '',
                'columns' => [
                    'componenteCurricular.codigoNome',
                    'setor.colegiado',
                    'pessoa.nome',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'controller' => 'sisaialunocomponentecurricular',
                    ]
                ],
            ])?>
            <p>Esqueceu de avaliar algum componente? Use o botão abaixo para avaliá-los:</p>
             <?php $form = ActiveForm::begin(); ?>
                <div class="form-group">
                    <?= Html::submitButton('Adicionar Novos Componentes', ['class' => 'btn btn-success btn-lg']) ?>
                </div>
            <?php ActiveForm::end(); ?>
    </div>

</div>