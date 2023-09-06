<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siscc-programa-componente-curricular-search">
    <div class="row">
    <?php $form = ActiveForm::begin([
    ]); ?>

        <?= $form->field($model, 'id_programa_componente_curricular')->dropDownList(ArrayHelper::map($programas,'id_programa_componente_curricular','semestreString')) ?>

        <?= $form->field($model, 'modificacoes')->dropDownList([0 => 'Não', 1 => 'Sim']) ?>
        <div style="color:red; font-size:12px; margin-bottom:15px">*Este campo será ignorado caso o programa tenha sido aprovado há mais de 5 anos.</div>
            <div class="form-group">
                <?= Html::submitButton('Importar', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
