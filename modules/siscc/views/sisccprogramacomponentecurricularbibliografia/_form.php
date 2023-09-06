<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siscc-programa-componente-curricular-bibliografia-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($bibliografia, 'nome')->textArea(['rows'=>'6']) ?>

    <?= $form->field($programaBibliografia, 'tipo_referencia')->dropDownList($programaBibliografia::TIPO_REFERENCIA) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
