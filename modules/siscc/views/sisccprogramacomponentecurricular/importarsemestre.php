<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\siscc\models\SisccSemestre;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siscc-programa-componente-curricular-search">
    <div class="row">
    <?php $form = ActiveForm::begin([
    ]); ?>
        <?= $form->field($model, 'id_semestre')->label('Semestre a ser importado')->dropDownList(
                ArrayHelper::map(SisccSemestre::find()
                     ->where(['semestre' => $semestre])
                     ->andWhere(['<','ano',$ano])
                    ->orderby('ano DESC')->all(),'id_semestre','string'))
        ?>
        
            <div class="form-group">
                <?= Html::submitButton('Importar', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
