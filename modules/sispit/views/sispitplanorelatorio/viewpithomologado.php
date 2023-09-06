<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\SispitAsset;

SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPlanoRelatorio */

$this->title = 'Plano Individual de Trabalho';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sispit-plano-relatorio-visualizar">

    <h1 class="cabecalho"><span><?= Html::encode($this->title) ?></span></h1>
    
    <?= $this->render('_view.php', $model->planoRelatorioToArray(0));?>

<?php
        echo Html::a('Salvar PDF', ['pdf', 'id' => $model->id_plano_relatorio, 'pit_rit' => 0], ['class' => 'btn btn-primary btn-print']);
?>
</div>
