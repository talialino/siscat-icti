<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\sisrh\models\SisrhComissao;

$servidores = ArrayHelper::map(SisrhComissao::find()->where(['eh_comissao_liga' => 1])->one()->pessoas,'id_pessoa','nome');

/* @var $this yii\web\View */
/* @var $model app\modules\sisliga\models\SisligaParecer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisliga-parecer-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'id_pessoa')->widget(Select2::class, [
        'data' => $servidores,
        'options' => ['placeholder' => 'Selecione o parecerista'],
    ])?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
