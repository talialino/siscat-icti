<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use app\assets\SisligaAsset;
use yii\data\ActiveDataProvider;
SisligaAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaLigaAcademica */

$this->title = 'Visualizar Liga';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisliga')), 'url' => ['/'.strtolower(Yii::t('app', 'sisliga'))]];
$this->params['breadcrumbs'][] = ['label' => 'Minhas Ligas Acadêmicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisliga-liga-view">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1> 
    
    <div class="clearfix" style="margin-bottom:10px"></div>

    <?php echo $this->render('_view', ['model' => $model]); ?>

    <?=Html::a('Salvar em PDF', ['pdf', 'id' => $model->id_liga_academica],
            ['class' => 'btn btn-primary btn-lg']
    )?>
    <?php if(Yii::$app->user->can('sisligaAdministrar')):?>
            <?= Html::a('Atualizar', ['update', 'id' => $model->id_liga_academica], ['class' => 'btn btn-warning pull-right btn-lg']) ?>
    <?php endif;?>
</div>
