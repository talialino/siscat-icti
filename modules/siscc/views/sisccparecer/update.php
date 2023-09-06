<?php

use yii\helpers\Html;
use app\assets\SisccAsset;
use app\modules\siscc\models\SisccParecerSearch;

SisccAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccParecer */

$this->title = $this->title = 'Definir Parecer';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Parecer', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Definir Parecer';

$penultimoParecer = SisccParecerSearch::ultimoParecer($model->id_programa_componente_curricular, 0);
?>
<div class="siscc-parecer-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?php if($penultimoParecer !== null && $penultimoParecer->tipo_parecerista == $model->tipo_parecerista
    && $penultimoParecer->comentario != null && strlen(trim($penultimoParecer->comentario)) > 0):?>
        <div class="siscc-parecer-comentario-docente">
            <h3>Mensagem dos Docentes Responsáveis pelo Programa</h3>
            <div class="mensagem">
                <?=$penultimoParecer->comentario?>
            </div>
        </div>
    <?php endif;?>

    <?= $this->render('/sisccprogramacomponentecurricular/_view.php', ['model' => $model->programaComponenteCurricular]);?>

    <?= $this->render('_view', ['model' => $model->programaComponenteCurricular, 'parecer' => $model]) ?>

    <?= $this->render('_form', ['model' => $model,]) ?>

</div>
