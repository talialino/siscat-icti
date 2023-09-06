<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

use app\assets\SispitAsset;
SispitAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitEnsinoComponente */

$this->title = 'Ensino';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
    Modal::begin([
        'header' => "<h3>$this->title</h3>",
        'id' => 'modal',
        'size' =>'modal-md',
        'options' => ['tabindex' => false],
    ]);
    echo "<div id='modalContent'><div style='text-align:center'>".Html::img('@web/images/carregando.gif')."</div></div>";
    Modal::end();
    $model->getTotal(0);
?>

<div class="sispit-ensino-componente-create">

<h1 class="cabecalho"><span><?= Html::encode($this->title) ?></span></h1>

    <div >
        <?= $this->render('/sispitensinocomponente/index', ['plano' => $model, 'model' => $ensinoComponente,]) ?>
    </div>

    <div >
        <?= $this->render('/sispitatividadecomplementar/_atividadecomplementar', ['plano' => $model, 'model' => $atividadeComplementar, ]) ?>
    </div>
</div>
