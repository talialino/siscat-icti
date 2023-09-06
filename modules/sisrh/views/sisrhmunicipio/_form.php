<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\sisrh\models\SisrhEstado;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhMunicipio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisrh-municipio-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_estado')->dropDownList(ArrayHelper::map(SisrhEstado::find()->all(),'id_estado','nome'),['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
