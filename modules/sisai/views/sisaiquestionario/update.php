<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use app\assets\SisaiAsset;
SisaiAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiQuestionario */

$this->title = 'Atualizar Questionário';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sisai'), 'url' => ['/'.strtolower('sisai')]];
$this->params['breadcrumbs'][] = ['label' => 'Questionarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->titulo, 'url' => ['view', 'id' => $model->id_questionario]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-questionario-update">

<h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?= Tabs::widget([
        'options' => ['class' => 'responsive'],
        'itemOptions' => ['class' => 'responsive'],
        'items' => [
            [
                'label' => 'Questionário',
                'content' => $this->render('_form', ['model' => $model])
            ],
            [
                'label' => 'Grupo de Perguntas',
                'active' => true,
                'content' => $this->render('/sisaigrupoperguntas/index', ['model' => $model]),
            ]
        ],
    ])?>

</div>
