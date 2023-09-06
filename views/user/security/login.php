<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\widgets\Connect;
use dektrium\user\models\LoginForm;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'SISCAT');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row" style="margin-top: 5%;">
    <div class="login-form">
        <div class="panel panel-default">
        <div class='logo'><?= Html::img('@web/images/icon-siscat.png', ['alt'=>'Logo Siscat', 'class'=>'']);?></div>
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                    'validateOnType' => false,
                    'validateOnChange' => false,
                    'options' => ['autocomplete' => 'off'],
                ]) ?>
            <div class="panel-body">
                <?php if ($module->debug): ?>
                    <?= $form->field($model, 'login', [
                        'inputOptions' => [
                            'autofocus' => 'autofocus',
                            'class' => 'form-control',
                            'tabindex' => '1']])->dropDownList(LoginForm::loginList());
                    ?>

                <?php else: ?>

                    <?= $form->field($model, 'login',
                        ['inputOptions' => [ 
                            'autofocus' => 'autofocus',
                            'class' => 'form-control', 
                            'placeholder'=> 'Usuário UFBA',
                            'tabindex' => '1'],
                         'errorOptions' => [
                            'encode' => false,
                            'class' => 'help-block',
                         ]]
                    )->label(false);
                    ?>

                <?php endif ?>

                <?php if ($module->debug): ?>
                    <div class="alert alert-warning">
                        <?= Yii::t('user', 'Password is not necessary because the module is in DEBUG mode.'); ?>
                    </div>
                <?php else: ?>
                    <?= $form->field(
                        $model,
                        'password',
                        ['inputOptions' => ['class' => 'form-control','placeholder'=> 'Senha', 'tabindex' => '2']])
                        ->passwordInput()
                        ->label(false) ?>
                <?php endif ?> 

                <?= Html::submitButton(
                    Yii::t('user', 'Acessar'),
                    ['class' => 'btn btn-primary btn-block', 'tabindex' => '4']
                ) ?>
            </div>
            <div class="panel-rodape row">
                <div class="col-sm-4" >
                    <a  href="https://autenticacao.ufba.br/u/redefinir-senha" style="color: #870202;">Esqueceu a senha?</a>
                </div>
                <div class="col-sm-4">
                    <a href="https://sistemasims.ufba.br/siscat" style="color: #1d0091;">SISCAT - Versão Antiga</a>
                </div>
                <div class="col-sm-4">
                    <a href="/siscat/site/aluno" style="color: #004200;">Primeiro Acesso Aluno!</a>
                </div>
            </div>
            
            
        <?php ActiveForm::end(); ?>

        </div>
        <?php if ($module->enableConfirmation): ?>
            <p class="text-center">
                <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
            </p>
        <?php endif ?>
        <?php if ($module->enableRegistration): ?>
            <p class="text-center">
                <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
            </p>
        <?php endif ?>
        <?= Connect::widget([
            'baseAuthUrl' => ['/user/security/auth'],
        ]) ?>
    </div>
</div>
