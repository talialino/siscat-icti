<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiAvaliacao */

$this->title = Yii::t('app', 'Create Sisai Avaliacao');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sisai Avaliacaos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-avaliacao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
