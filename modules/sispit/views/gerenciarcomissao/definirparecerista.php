<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\sisrh\models\SisrhComissao;

$docentes = ArrayHelper::map(SisrhComissao::find()->where(['eh_comissao_pit_rit' => 1])->one()->pessoas,'id_pessoa','nome');

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitParecer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-parecer-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'id_pessoa')->widget(Select2::class, [
        'data' => $docentes,
        'options' => ['placeholder' => 'Selecione o docente'],
    ])?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
