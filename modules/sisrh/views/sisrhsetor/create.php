<?php

use yii\helpers\Html;
use app\assets\SisrhAsset;

SisrhAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetor */

$this->title = Yii::t('app','Create Sector');
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Sectors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisrh-setor-create">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => new \yii\data\ArrayDataProvider, //passado somente porque _form precisa desse parâmetro pois também é a página do update, mas esse parâmetro não tem serventia, é, está certo, é mais uma gambiarra.
    ]) ?>

</div>
