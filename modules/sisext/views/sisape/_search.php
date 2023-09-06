<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisape\models\SisapeProjeto;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeProjetoSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="sisape-projeto-search">

    <?php $form = ActiveForm::begin([
        // 'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <?= $form->field($model, 'titulo',['options' => ['class' => 'col-md-12']]) ?>
    <?= $form->field($model, 'id_pessoa',['options' => ['class' => 'col-md-5']])->widget(Select2::className(), [
            'data' => ArrayHelper::map(SisrhPessoa::find()->orderby('nome')->all(),'id_pessoa','nome'),
            'options' => ['placeholder' => 'Selecione a pessoa'],
            'pluginOptions' => ['allowClear' =>true]
    ]) ?>
    <?= $form->field($model, 'tipo_projeto',['options' => ['class' => 'col-md-2']])->dropDownList(SisapeProjeto::TIPO_PROJETO, ['prompt' => '']) ?>
    <?= $form->field($model, 'ano',['options' => ['class' => 'col-md-2']])->input('number') ?>
    <?= $form->field($model, 'area_atuacao',['options' => ['class' => 'col-md-3']]) ?>
    
    <div class="form-group col-md-3" style="margin-top:14px">
        <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="clearfix"></div>


    <?php ActiveForm::end(); ?>

</div>
