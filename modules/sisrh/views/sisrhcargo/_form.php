<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\sisrh\models\SisrhCategoria;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhCargo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisrh-cargo-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
    <div class='col-md-12 tabs-conteudo' style="border: 1px solid #e2e2e2">

    <div class='col-md-6'>
        <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>
    </div>
    <div class='col-md-6'>
        <?= $form->field($model, 'id_categoria')->dropDownList(ArrayHelper::map(SisrhCategoria::find()->all(),'id_categoria','nome'),['prompt' => '']) ?>
    </div>
    <div class='col-md-12'>
    <?= $form->field($model, 'atribuicoes')->widget(TinyMce::className(), [
    'options' => ['rows' => 20],
    'language' => 'pt_BR',
    'clientOptions' => [
        'menubar' => false,
        'toolbar' => "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
    ]
]);?>
    </div>

    <div class="form-group col-md-12">
        <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
    <div class="clearfix">
</div>
