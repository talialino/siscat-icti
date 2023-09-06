<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use app\modules\sisrh\models\SisrhSetor;
use app\modules\sisrh\models\SisrhSetorPessoa;
use app\modules\sisrh\models\SisrhComissao;
use app\modules\sisrh\models\SisrhComissaoPessoa;

use dektrium\rbac\models\AuthItem;
use dektrium\rbac\models\Search;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItemSisrhSetor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-sisrh-setor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->dropDownList((new Search(Item::TYPE_ROLE))->getNameList(),['prompt' => '']) ?>

    <?= $form->field($model, 'id_setor')->dropDownList(ArrayHelper::map(SisrhSetor::find()->all(),'id_setor','nome'),['prompt' => '']) ?>

    <?= $form->field($model, 'id_comissao')->dropDownList(ArrayHelper::map(SisrhComissao::find()->all(),'id_comissao','nome'),['prompt' => '']) ?>

    <?= $form->field($model, 'funcao')->dropDownList(SisrhSetorPessoa::FUNCOES,['prompt' => '']) ?>

    <div class="form-group">
    <?= Html::dropDownList('funcaoComissao', null,SisrhComissaoPessoa::FUNCOES,['prompt' => 'Se for comissão, selecione aqui']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
