<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeProjetoIntegranteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisape-projeto-integrante-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_projeto_integrante') ?>

    <?= $form->field($model, 'id_projeto') ?>

    <?= $form->field($model, 'id_integrante_externo') ?>

    <?= $form->field($model, 'id_pessoa') ?>

    <?= $form->field($model, 'funcao') ?>

    <?php // echo $form->field($model, 'id_aluno') ?>

    <?php // echo $form->field($model, 'carga_horaria') ?>

    <?php // echo $form->field($model, 'vinculo') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
