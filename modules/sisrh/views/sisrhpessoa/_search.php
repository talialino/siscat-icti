<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhPessoaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sisrh-pessoa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_pessoa') ?>

    <?= $form->field($model, 'id_user') ?>

    <?= $form->field($model, 'siape') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'dt_nascimento') ?>

    <?php // echo $form->field($model, 'sexo') ?>

    <?php // echo $form->field($model, 'estado_civil') ?>

    <?php // echo $form->field($model, 'id_naturalidade') ?>

    <?php // echo $form->field($model, 'nacionalidade') ?>

    <?php // echo $form->field($model, 'nome_pai') ?>

    <?php // echo $form->field($model, 'nome_mae') ?>

    <?php // echo $form->field($model, 'escolaridade') ?>

    <?php // echo $form->field($model, 'endereco') ?>

    <?php // echo $form->field($model, 'complemento') ?>

    <?php // echo $form->field($model, 'numero') ?>

    <?php // echo $form->field($model, 'bairro') ?>

    <?php // echo $form->field($model, 'id_estado') ?>

    <?php // echo $form->field($model, 'id_municipio') ?>

    <?php // echo $form->field($model, 'cep') ?>

    <?php // echo $form->field($model, 'telefone') ?>

    <?php // echo $form->field($model, 'celular') ?>

    <?php // echo $form->field($model, 'emails') ?>

    <?php // echo $form->field($model, 'cpf') ?>

    <?php // echo $form->field($model, 'titulo_eleitor') ?>

    <?php // echo $form->field($model, 'pis_pasep') ?>

    <?php // echo $form->field($model, 'numero_rg') ?>

    <?php // echo $form->field($model, 'orgao_rg') ?>

    <?php // echo $form->field($model, 'id_estado_rg') ?>

    <?php // echo $form->field($model, 'expedicao') ?>

    <?php // echo $form->field($model, 'carteira_trabalho') ?>

    <?php // echo $form->field($model, 'serie_cart_trabalho') ?>

    <?php // echo $form->field($model, 'id_estado_cart_trabalho') ?>

    <?php // echo $form->field($model, 'regime_trabalho') ?>

    <?php // echo $form->field($model, 'dt_ingresso_orgao') ?>

    <?php // echo $form->field($model, 'id_cargo') ?>

    <?php // echo $form->field($model, 'id_classe_funcional') ?>

    <?php // echo $form->field($model, 'situacao') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app','Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app','Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
