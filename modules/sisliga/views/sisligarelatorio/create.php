<?php

use yii\helpers\Html;
use app\assets\SisligaAsset;
SisligaAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaRelatorio */

$this->title = 'Criar Relatório';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisliga')), 'url' => ['/'.strtolower(Yii::t('app', 'sisliga'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Relatórios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisliga-relatorio-create">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchLiga' => $searchLiga,
    ]) ?>

</div>
