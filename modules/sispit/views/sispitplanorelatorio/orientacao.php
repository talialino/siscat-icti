<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

use app\assets\SispitAsset;
SispitAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitEnsinoComponente */

$this->title = 'Orientação';
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
?>
<div class="sispit-ensino-componente-create">

    <h1 class="cabecalho"><span><?= Html::encode($this->title) ?></span></h1>

    <div class="col-lg-6">
        <?= $this->render('/sispitorientacaoacademica/index', ['plano' => $model, 'model' => $orientacaoAcademica, 'atividadeComplementar' => $atividadeComplementar,]) ?>
    </div>

    <div class="col-lg-6">
        <?= $this->render('/sispitmonitoria/index', ['plano' => $model, 'model' => $monitoria, ]) ?>
    </div>

    <div class="col-sm-12" >
        <?= $this->render('/sispitligaacademica/index', ['plano' => $model, 'model' => $ligaAcademica, ]) ?>
    </div>

    <div class="col-sm-12" >
        <?= $this->render('/sispitensinoorientacao/index', ['plano' => $model, 'model' => $ensinoOrientacao, ]) ?>
    </div>
    <div style="clear:both"></div>
</div>
