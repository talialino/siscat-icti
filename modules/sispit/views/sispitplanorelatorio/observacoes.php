<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\SispitAsset;
SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitPlanoRelatorio */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Observações';
$this->params['breadcrumbs'][] = ['label' => strtoupper('sispit'), 'url' => ['/'.strtolower('sispit')]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sispit-plano-relatorio-form">

    <h1 class="cabecalho"><span><?= Html::encode($this->title) ?></span></h1>
    <div class="panel panel-primary">
        <div class="panel-heading"> </div>
        <div style="margin:10px">
        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
            <?php if($model->isRitAvailable()):?>
            
                <?= $form->field($model, 'observacao_rit',['template' => "{input}\n{hint}\n{error}"])->textarea(['rows' => 20, 'readOnly' => !$model->isRitEditable()]) ?>

                <label class = "control-label" for = "sispitplanorelatorio-observacao_pit">Observações do PIT</label>
                
            <?php endif;?>
        <?= $form->field($model, 'observacao_pit',['template' => "{input}\n{hint}\n{error}"])->textarea(['rows' => 20, 'readOnly' => !$model->isPitEditable()]) ?>
        
        <div class="form-group">
            <?= $model->isEditable() ? Html::submitButton('Salvar', ['class' => 'btn btn-success']) : '' ?>
        </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
