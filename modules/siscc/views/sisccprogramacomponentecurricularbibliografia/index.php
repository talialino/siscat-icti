<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use app\assets\SisccAsset;
SisccAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografiaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Referências Bibliográficas';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div  class="siscc-programa-componente-curricular-bibliografia-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <div class="col-sm-offset-3 col-sm-6" style="background:white; padding:20px 0; border:1px solid #ccc">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <hr style="border-color:#ccc">
    <div class="col-sm-12">
    <p><strong>E agora selecione os campos e o formato de exportação</strong></p>
    <?= ExportMenu::widget([
        'exportConfig' => [
            ExportMenu::FORMAT_EXCEL => false,
            ExportMenu::FORMAT_PDF => false
        ],
        'dataProvider' => $dataProvider,
        'columns' => [
            'referencia',
            'tipo',
            'programaComponenteCurricular.componenteCurricular.codigo_componente',
            'programaComponenteCurricular.colegiado',
            'programaComponenteCurricular.semestreString',
        ],
    ]); ?>
    </div>
    </div>
    <div class="clearfix"></div>
</div>
