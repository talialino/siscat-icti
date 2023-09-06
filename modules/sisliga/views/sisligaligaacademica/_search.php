<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\sisrh\models\SisrhPessoa;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisligaLigaAcademicaSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="sisliga-liga-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <?= $form->field($model, 'nome',['options' => ['class' => 'col-md-12']]) ?>
    <?= $form->field($model, 'id_pessoa',['options' => ['class' => 'col-md-4']])->widget(Select2::class, [
            'data' => ArrayHelper::map(SisrhPessoa::find()->orderby('nome')->all(),'id_pessoa','nome'),
            'options' => ['placeholder' => 'Selecione a pessoa'],
            'pluginOptions' => ['allowClear' =>true]
    ]) ?>
    <?= $form->field($model, 'situacao',['options' => ['class' => 'col-md-6']])->dropDownList($model::SITUACAO, ['prompt' => '']) ?>
    
    <div class="form-group col-md-3" style="margin-top:14px">
        <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="clearfix"></div>


    <?php ActiveForm::end(); ?>

</div>
