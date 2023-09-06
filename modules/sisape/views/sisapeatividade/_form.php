<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeAtividade */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisape-atividade-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <div class="col-md-6"><?= $form->field($model, 'data_inicio')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,]) ?></div>

    <div class="col-md-6"><?= $form->field($model, 'data_fim')->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,])?></div>

    <div class="col-md-12"><?= $form->field($model, 'descricao_atividade')->textarea(['rows' => 6]) ?></div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salvar'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
