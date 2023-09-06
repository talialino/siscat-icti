<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiPerguntaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisai-pergunta-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_pergunta') ?>

    <?= $form->field($model, 'descricao') ?>

    <?= $form->field($model, 'tipo_pergunta') ?>

    <?= $form->field($model, 'id_questionario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
