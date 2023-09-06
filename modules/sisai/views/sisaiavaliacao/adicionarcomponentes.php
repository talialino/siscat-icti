<?php

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use app\assets\SisaiAsset;
SisaiAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisai\models\AdicionarComponentesForm */

$this->title = 'Adicionar Componentes Curriculares';
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-avaliacao-create">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    
    <div class="sisai-adicionar-componentes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'componentes')->widget(Select2::class,[
        'data' => $model::listaComponentes(),
        'options' => ['placeholder' => 'Digite os componentes que deseja adicionar', 'multiple' => true]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salvar'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>

</div>