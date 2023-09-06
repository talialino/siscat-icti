<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiAvaliacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisai-avaliacao-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_semestre')->textInput() ?>

    <?= $form->field($model, 'id_aluno')->textInput() ?>

    <?= $form->field($model, 'id_pessoa')->textInput() ?>

    <?= $form->field($model, 'tipo_avaliacao')->textInput() ?>

    <?= $form->field($model, 'situacao')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salvar'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
