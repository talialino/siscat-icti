<?php

use yii\helpers\Html;
use app\assets\SisligaAsset;
SisligaAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaLigaAcademica */

$this->title = Yii::t('app', 'Criar Liga Acadêmica');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisliga')), 'url' => ['/'.strtolower(Yii::t('app', 'sisliga'))]];
$this->params['breadcrumbs'][] = ['label' => 'Minhas Ligas Acadêmicas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisliga-liga-create">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tab' => 0,
    ]) ?>

</div>
