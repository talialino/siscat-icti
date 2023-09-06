<?php

use yii\helpers\Html;
use app\assets\SisapeAsset;
SisapeAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeProjeto */

$this->title = Yii::t('app', 'Criar Projeto');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisape')), 'url' => ['/'.strtolower(Yii::t('app', 'sisape'))]];
$this->params['breadcrumbs'][] = ['label' => 'Meus Projetos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisape-projeto-create">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tab' => 0,
    ]) ?>

</div>
