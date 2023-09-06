<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeIntegranteExterno */

$this->title = Yii::t('app', 'Create Sisape Integrante Externo');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sisape Integrante Externos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-integrante-externo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
