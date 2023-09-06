<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhCategoria */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisrh-categoria-form">
<div class='col-md-12 tabs-conteudo' style="border: 1px solid #e2e2e2">
    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <div class='col-md-4'>
    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="form-group col-md-12">
        <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

</div>
