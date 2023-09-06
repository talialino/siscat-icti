<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiPergunta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisai-pergunta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'tipo_pergunta')->dropDownList($model::TIPO_PERGUNTA)?>

    <?= $form->field($model, 'nsa')->dropDownList($model::NAO_SE_APLICA) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
