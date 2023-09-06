<?php

use yii\helpers\Html;
use app\modules\sispit\models\SispitParecerSearch;

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitParecer */

$this->title = $this->title = 'Definir Parecer';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = ['label' => 'Parecer', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Definir Parecer';

$penultimoParecer = SispitParecerSearch::ultimoParecer($model->id_plano_relatorio, 0);

?>
<div class="sispit-parecer-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?php if($penultimoParecer !== null && $penultimoParecer->tipo_parecerista == $model->tipo_parecerista
    && $penultimoParecer->pit_rit == $model->pit_rit && $penultimoParecer->comentario != null && strlen(trim($penultimoParecer->comentario)) > 0):?>
        <div class="sispit-parecer-comentario-docente">
            <h3>Mensagem de <?php echo $model->planoRelatorio->pessoa->nome ?></h3>
            <div class="mensagem">
                <?=$penultimoParecer->comentario?>
            </div>
        </div>
    <?php endif;?>
    <?= $this->render('/sispitplanorelatorio/_view.php', $model->planoRelatorio->planoRelatorioToArray($model->pit_rit));?>

    <?= $this->render('_form', ['model' => $model,]) ?>

</div>
