<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;

SisaiAsset::register($this);


/* @var $this yii\web\View */
/* @var $avaliacao app\modules\sisai\models\SisaiAvaliacao */

$this->title = 'Importar Componentes';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-avaliacao-final">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    
        <div class="sisai-final-discente box box-info">
            <div class="mensagem-inicial">
            <p>
                A seguir estão os componentes que você ministrou nesse semestre. Verifique se as informações estão corretas antes de prosseguir.
                Caso encontre algum problema, entre em contato com a CAC para corrigir as informações no SISCC antes de realizar a avaliação.
            </p>
            </div>
            <?php if($avaliacao->situacao == 99):?>
                <div class="mensagem-inicial">
                    <p>Se você já avaliou todos os componentes abaixo, ao clicar em Avançar, nenhuma alteração será feita e você retornará para a página final.</p>
                </div>
            <?php endif;?>
            
            <?= GridView::widget([
                'dataProvider' => new ActiveDataProvider(['query' => $query]),
                'panel' => ['type' => 'primary', 'heading' => 'Componentes Ministrados'],
                'toolbar' =>  [['content' => false,],],
                'summary' => '',
                'columns' => [
                    'componente',
                    'colegiado',
                ],
            ])?>
             <?php $form = ActiveForm::begin(); ?>
                <div class="form-group">
                    <?= Html::submitButton('Avançar', ['class' => 'btn btn-success']) ?>
                </div>
            <?php ActiveForm::end(); ?>
    </div>

</div>