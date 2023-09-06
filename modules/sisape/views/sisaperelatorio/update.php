<?php

use yii\helpers\Html;


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

$this->title = $this->title = Yii::t('app', 'Atualizar Relatorio');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Meus Relatorios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Visualizar', 'url' => ['view', 'id' => $model->id_relatorio]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sisape-relatorio-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchProjeto' => $searchProjeto,
    ]) ?>

</div>
