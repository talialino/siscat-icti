<?php

use yii\helpers\Html;
//use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;
use app\assets\SispitAsset;

SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPlanoRelatorio */

$this->title = $pit_rit ? "Relatório Individual de Trabalho".($model->ehRitParcial() ? ' (Parcial)' : '') : "Plano Individual de Trabalho";

?>
<div class="sispit-plano-relatorio-visualizar">

    <h1 class="cabecalho"><span><?= Html::encode($this->title) ?></span></h1>
    
    <?= $this->render('/sispitplanorelatorio/_view.php', $model->planoRelatorioToArray($pit_rit));?>

<?php
    if($model->status == 9 || $model->status == 19)
        echo Html::a('Salvar PDF', ['pdf', 'id' => $model->id_plano_relatorio], ['class' => 'btn btn-primary btn-print']);
?>
</div>
