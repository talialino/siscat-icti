<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\sisrh\models\SisrhPessoa;

$docentes = ArrayHelper::map(SisrhPessoa::find()->innerJoinWith('sisrhSetorPessoas')->where(['situacao' => 1, 'id_cargo' => 1, 'id_setor' => $id_setor])->orderby('nome')->all(),'id_pessoa','nome');

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccParecer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siscc-parecer-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'id_pessoa')->widget(Select2::class, [
        'data' => $docentes,
        'options' => ['placeholder' => 'Selecione o docente'],
    ])?>

    <?= $form->field($model, 'edicao')->dropDownList([0 => 'Não', 1 => 'Sim'])?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
