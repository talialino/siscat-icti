<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\assets\SisligaAsset;
SisligaAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaRelatorio */

$this->title = 'Visualizar Relatório';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisliga')), 'url' => ['/'.strtolower(Yii::t('app', 'sisliga'))]];
$this->params['breadcrumbs'][] = ['label' => 'Meus Relatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisliga-relatorio-view">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    
    <div class="clearfix" style="margin-bottom:10px"></div>

    <?php echo $this->render('_view', ['model' => $model]); ?>

    <?php echo $this->render('/sisligaligaacademica/_view', ['model' => $model->ligaAcademica]); ?>
    
    <?=Html::a('Salvar em PDF', ['pdf', 'id' => $model->id_relatorio],
                ['class' => 'btn btn-primary btn-lg']
                
        )?>
    <?php if(Yii::$app->user->can('sisligaAdministrar')):?>
            <?= Html::a('Atualizar', ['update', 'id' => $model->id_relatorio, 'admin' => true], ['class' => 'btn btn-warning pull-right btn-lg']) ?>
    <?php endif;?>
    

</div>
