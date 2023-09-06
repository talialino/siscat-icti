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
    
        <div class="sisai-final-docente box box-info">
            <div class="mensagem-sucesso">
                <p>Obrigada por sua avaliação!</p>
                <p>Sua contribuição é muito importante para o processo avaliativo do nosso instituto, o que nos permite estar sempre aperfeiçoando nossos serviços.
            </div>
            <?= GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $avaliacao->getSisaiProfessorComponenteCurriculares()]),
                'panel' => ['type' => 'primary', 'heading' => 'Componentes Avaliados'],
                'toolbar' =>  [['content' => false,],],
                'summary' => '',
                'columns' => [
                    'componenteCurricular.codigoNome',
                    'setor.colegiado',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'controller' => 'sisaiprofessorcomponentecurricular',
                        'visible' => Yii::$app->session->has('superuser'),
                    ]
                ],
            ])?>
            <?php if(Yii::$app->session->has('superuser')):?>
            <div class="mensagem-inicial">
            <span>Você removeu alguma avaliação, ou foi feita alguma alteração no SISCC? Você pode usar o botão abaixo para atualizar as informações:</span>
            </div> 
            <?php $form = ActiveForm::begin(); ?>
                <div class="form-group">
                    <?= Html::submitButton('Importar Componentes', ['class' => 'btn btn-success']) ?>
                </div>
            <?php ActiveForm::end(); ?>
            <?php endif;?>
    </div>

</div>