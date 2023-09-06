<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

$campo = ($model->isRitAvailable() ? 'data_homologacao_nucleo_rit' : 'data_homologacao_nucleo_pit')
?>

<div class="sispit-aprovar-planorelatorio">

    <div class="sispit-parecer-form">
    
        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>

        <?= $form->field($model, $campo)->widget(DateControl::classname(), ['type'=>DateControl::FORMAT_DATE,])?>

        <div class="form-group">
            <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <?= DetailView::widget([
    'model' => $parecer,
    'panel' => ['type' => 'primary', 'heading' => 'Último Parecer', 'before' => false,
    'after' => false],
    'enableEditMode' => false,
    'attributes' => [
        'parecer',
        [
            'columns' => [
                ['attribute' => 'nomeParecerista'],
                ['attribute' => 'data', 'format' => 'date', 'label' => 'Data'],
            ]
        ]
    ]
]);?>
</div>