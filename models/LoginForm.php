<?php

namespace app\models;

use app\modules\sisai\models\SisaiAluno;
use Yii;
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
    public $verifyCode;
   
    /** @inheritdoc */
    public function rules()
    {
        $rules = [
            'loginTrim' => ['login', 'trim'],
            'requiredFields' => [['login', 'password'], 'required'],
            'passwordValidate' => [
                'password',
                function ($attribute) {
                    if($this->user === null){
                        return;
                    }

                    $ldap_con = ldap_connect("ldap.intranet.ufba.br");
                    $username = str_replace('@ufba.br','',$this->user->username);
                    if(!@ldap_bind($ldap_con, "$username@ufba.br", $this->password))
                        if(!@ldap_bind($ldap_con, "$username@intranet.ufba.br", $this->password))
                            $this->addError($attribute, 'Senha incorreta!');
                    ldap_close($ldap_con);
                    
                }
            ],
            'confirmationValidate' => [
                'login',
                function ($attribute) {
                    if ($this->user === null){
                        $this->addError($attribute, '<div class="login-form-msg"><div>Usuário não cadastrado, verifique se você 
                        digitou corretamente.</div><div>Se você for aluno(a) e ainda não cadastrou o 
                        seu usuário, <a href="/siscat/site/aluno">
                        <strong>clique aqui</strong></a>.</div><div>Se você for docente ou técnico e seu usuário não 
                        está cadastrado, <a href="/siscat/site/contactcgdp"  
                        ><strong>clique aqui</strong></a></div></div>');
                    }
                }
            ],
        ];

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
            {
                $aluno = SisaiAluno::usuarioAtivo($this->user->id);
                if(!isset($aluno))
                    throw new \yii\web\UnauthorizedHttpException("Pessoa não encontrada ou situação não está ativa.");
                else
                    Yii::$app->session->set('siscat_aluno', $aluno);
            }
            else
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
