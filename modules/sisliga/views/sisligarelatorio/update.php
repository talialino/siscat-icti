<?php

use yii\helpers\Html;
use app\assets\SisligaAsset;
SisligaAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaRelatorio */

$this->title = $this->title = Yii::t('app', 'Atualizar Relatorio');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisliga')), 'url' => ['/'.strtolower(Yii::t('app', 'sisliga'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Meus Relatorios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Visualizar', 'url' => ['view', 'id' => $model->id_relatorio]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sisliga-relatorio-update">

<h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchLiga' => $searchLiga,
    ]) ?>

</div>
