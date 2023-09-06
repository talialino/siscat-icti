<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiColegiadoSemestreAtuvSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisai-colegiado-semestre-atuv-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_colegiado_semestre_atuv') ?>

    <?= $form->field($model, 'colegiados_liberados') ?>

    <?= $form->field($model, 'id_semestre') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
