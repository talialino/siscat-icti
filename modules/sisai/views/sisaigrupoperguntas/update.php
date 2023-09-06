<?php

use yii\helpers\Html;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiGrupoPerguntas */

$this->title = $this->title = Yii::t('app', 'Atualizar Grupo de Perguntas');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = ['label' => 'Atualizar Questionario', 'url' => ['/sisai/sisaiquestionario/update', 'id' => $model->id_questionario]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sisai-grupo-perguntas-update">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

<?= $this->render('/sisaipergunta/index', ['model' => $model]) ?>

</div>
