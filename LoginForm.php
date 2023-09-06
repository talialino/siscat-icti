<?php

namespace app\models;

use dektrium\user\Finder;
use dektrium\user\helpers\Password;
use dektrium\user\traits\ModuleTrait;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use yii\base\Model;
use dektrium\user\models\LoginForm as BaseLoginForm;
use app\modules\sisrh\models\SisrhPessoa;

/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class LoginForm extends BaseLoginForm
{
   
    /** @inheritdoc */
    public function rules()
    {
        $rules = [
            'loginTrim' => ['login', 'trim'],
            'requiredFields' => [['login'], 'required'],
            'confirmationValidate' => [
                'login',
                function ($attribute) {
                    if ($this->user !== null) {
                        $confirmationRequired = $this->module->enableConfirmation
                            && !$this->module->enableUnconfirmedLogin;
                        if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                            $this->addError($attribute, Yii::t('user', 'You need to confirm your email address'));
                        }
                        if ($this->user->getIsBlocked()) {
                            $this->addError($attribute, Yii::t('user', 'Your account has been blocked'));
                        }
                    }
                    else{
                        $this->addError($attribute, 'Usuário não cadastrado. Envie um e-mail para cgdp.ims@ufba.br informando nome, matrícula, e-mail UFBA e telefones para liberar seu acesso.');
                    }
                }
            ],
            'rememberMe' => ['rememberMe', 'boolean'],
        ];

        $rules = array_merge($rules, [
            'requiredFields' => [['login', 'password'], 'required'],
            'passwordValidate' => [
                'password',
                function ($attribute) {
                    if($this->user === null)
                        return;

                    $ldap_con = ldap_connect("ldap.intranet.ufba.br");
                    $username = str_replace('@ufba.br','',$this->user->username);
                    if(!@ldap_bind($ldap_con, "$username@ufba.br", $this->password))
                        if(!@ldap_bind($ldap_con, "$username@intranet.ufba.br", $this->password))
                            $this->addError($attribute, 'Senha incorreta!');
                    ldap_close($ldap_con);
                    
                }
            ]
        ]);

        return $rules;
    }

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $this->user) {
            $session = Yii::$app->session;
            // Verifica se a pessoa está ativa
            
            $pessoa = SisrhPessoa::usuarioAtivo($this->user->id);
            if(!isset($pessoa))
                throw new \yii\web\UnauthorizedHttpException("Pessoa não encontrada ou situação não está ativa.");
            //Insere nome da pessoa na sessão, para evitar buscar do banco toda vez que carregar uma página diferente
            Yii::$app->session->set('siscat_pessoa', $pessoa);
            $isLogged = Yii::$app->getUser()->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);

            if ($isLogged) {
                //Joga o ultimo login na sessão para ser exibido pelo cabeçalho
                $session['last_login'] = $this->user->last_login_at;
                $this->user->updateAttributes(['last_login_at' => time()]);
            }

            return $isLogged;
        }

        return false;
    }
}
