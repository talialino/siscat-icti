<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\sisrh\models\SisrhPessoa;

$docentes = ArrayHelper::map(SisrhPessoa::find()->innerJoinWith('sisrhSetorPessoas')->where(['situacao' => 1, 'id_cargo' => 1, 'id_setor' => $projeto->id_setor])->andWhere(['<>','sisrh_pessoa.id_pessoa',$projeto->id_pessoa])->orderby('nome')->all(),'id_pessoa','nome');

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitParecer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sispit-parecer-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'id_pessoa')->widget(Select2::className(), [
        'data' => $docentes,
        'options' => ['placeholder' => 'Selecione o docente'],
    ])?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
