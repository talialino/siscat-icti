<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiPergunta */

$this->title = $this->title = 'Atualizar Pergunta';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisai'), 'url' => ['/'.strtolower('sisai')]];
$this->params['breadcrumbs'][] = ['label' => 'Questionários', 'url' => ['/sisai/sisaiquestionario/index']];
$this->params['breadcrumbs'][] = ['label' => $model->questionario->titulo, 'url' => ['/sisai/sisaiquestionario/update', 'id' => $model->questionario->id_questionario]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-pergunta-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
