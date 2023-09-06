<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhPessoa */

$this->title = Yii::t('app','Create Person');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','People'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-pessoa-create">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'ocorrencia' => $ocorrencia,
    ]) ?>

</div>
