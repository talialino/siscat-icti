<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use app\modules\sisrh\models\SisrhPessoa;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularPessoa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siscc-programa-componente-curricular-pessoa-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

    <?= $form->field($model, 'id_pessoa')->widget(Select2::className(),[
            'data' => ArrayHelper::map(SisrhPessoa::find()->where(['id_cargo' => [1,2,3,4]])->orderby('nome')->all(),'id_pessoa', 'nome'),
            'options' => ['placeholder' => 'Selecione docente'],
            'pluginOptions' => ['allowClear' =>true]
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
