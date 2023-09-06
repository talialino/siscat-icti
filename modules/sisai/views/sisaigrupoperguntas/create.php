<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
SisaiAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiGrupoPerguntas */

$this->title = Yii::t('app', 'Criar Grupo de Perguntas');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Atualizar Questionario'), 'url' => ['/sisai/sisaiquestionario/update', 'id' => $model->id_questionario]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-grupo-perguntas-create">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
