<?php

use yii\helpers\Html;
use app\assets\SisapeAsset;
SisapeAsset::register($this);

$this->registerJs(
    "function toogleJustificativa(campo){
        if($(campo).val() < 3)
            $('#justificativa').show();
        else
            $('#justificativa').hide();
    }",
    yii\web\View::POS_HEAD,
    'my-button-handler'
);
/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeRelatorio */

$this->title = 'Criar Relatório';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sisape Relatorios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-relatorio-create">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchProjeto' => $searchProjeto,
    ]) ?>

</div>
