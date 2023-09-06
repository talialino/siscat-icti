<?php

use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhSetor;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\SisaiColegiadoSemestreAtuv */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisai-colegiado-semestre-atuv-form box box-primary">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'colegiados_liberados')->widget(MultipleInput::class,[
            
            'columns' => [
                [
                    'name'  => 'colegiados_liberados',
                    'type'  => 'dropDownList',
                    
                    'items' => ArrayHelper::map( SisrhSetor::find()->where('eh_colegiado = 1')->all(),'id_setor','colegiado'),
                ],
            ]

        ]) ?>

    <?= $form->field($model, 'id_semestre')->dropDownList(
            ArrayHelper::map(SisccSemestre::find()->orderby('ano DESC, semestre DESC')->all(),'id_semestre','string'),
            ['prompt' => 'Selecione o semestre',]
        ) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salvar'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
