<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\modules\sisai\models\SisaiAluno;
use dektrium\user\widgets\Connect;
use dektrium\user\models\LoginForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$alunos = ArrayHelper::map(SisaiAluno::getAtivos('id_aluno, nome, matricula'),'id_aluno','matriculaNome');

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'SISCAT');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row" style="margin-top: 5%;">
    <div class="login-form col-md-6 col-md-offset-3">
        <div class="box box-primary">
            
            <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                    'validateOnType' => false,
                    'validateOnChange' => false,
                    'options' => ['autocomplete' => 'off'],
                ]) ?>
            <div class="box-header with-border">
                <h3 class="box-title">CADASTRO DO PRIMEIRO ACESSO ALUNO</h3>
            </div>
            <div class="box-body">
            <?= $form->field($model, 'id_aluno')->widget(Select2::class, [
                'data' => $alunos,
                'options' => ['placeholder' => ''],
            ])->label('Discente')?>

                <?= $form->field($model, 'login')->label('Login da UFBA') ?>

                <?= $form->field($model, 'password')->passwordInput()->label('Senha da UFBA') ?>

                <div class="form-group">
                    <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
            </div>
        <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>