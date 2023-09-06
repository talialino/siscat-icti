<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisape\models\SisapeRelatorioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisape-relatorio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_relatorio') ?>

    <?= $form->field($model, 'id_projeto') ?>

    <?= $form->field($model, 'situacao_projeto') ?>

    <?= $form->field($model, 'alunos_orientados') ?>

    <?= $form->field($model, 'resumos_publicados') ?>

    <?php // echo $form->field($model, 'artigos_publicados') ?>

    <?php // echo $form->field($model, 'artigos_aceitos') ?>

    <?php // echo $form->field($model, 'relatorio_agencia') ?>

    <?php // echo $form->field($model, 'deposito_patente') ?>

    <?php // echo $form->field($model, 'outros_indicadores') ?>

    <?php // echo $form->field($model, 'consideracoes_finais') ?>

    <?php // echo $form->field($model, 'data_relatorio') ?>

    <?php // echo $form->field($model, 'data_aprovacao_nucleo') ?>

    <?php // echo $form->field($model, 'data_aprovacao_copex') ?>

    <?php // echo $form->field($model, 'data_homologacao') ?>

    <?php // echo $form->field($model, 'sessao_congregacao') ?>

    <?php // echo $form->field($model, 'tipo_sessao') ?>

    <?php // echo $form->field($model, 'situacao') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
