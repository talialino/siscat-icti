<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\sisrh\models\SisrhCategoria;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhClasseFuncional */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisrh-classe-funcional-form">
    <div class='col-md-12 tabs-conteudo' style="border: 1px solid #e2e2e2">
    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
    <div class='col-md-6'>
    <?= $form->field($model, 'denominacao')->textInput(['maxlength' => true]) ?>
    </div>
    <div class='col-md-6'>
    <?= $form->field($model, 'id_categoria')->dropDownList(ArrayHelper::map(SisrhCategoria::find()->all(),'id_categoria','nome'),['prompt' => '']) ?>
    </div>
    <div class="form-group col-md-12">
        <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
