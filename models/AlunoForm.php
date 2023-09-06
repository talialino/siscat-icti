<?php

namespace app\models;

use app\modules\sisai\models\SisaiAluno;
use Yii;
use dektrium\user\models\LoginForm;
use dektrium\user\models\User;
use Exception;
use yii\web\UnauthorizedHttpException;

/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class AlunoForm extends LoginForm
{
    public $id_aluno;
   
    /** @inheritdoc */
    public function rules()
    {
        $rules = [
            'loginTrim' => ['login', 'trim'],
            'requiredFields' => [['login', 'password', 'id_aluno'], 'required'],
            'confirmationValidate' => [
                'login',
                function ($attribute) {
                    if ($this->user !== null)
                        $this->addError($attribute, 'Este usuário já está cadastrado no sistema.');
                }
            ],
            'alunoValidate' => ['id_aluno',
                function($attribute){
                    $aluno = SisaiAluno::findOne($this->id_aluno);
                    if($aluno->id_user != null)
                        $this->addError($attribute, "$aluno->nome já possui um usuário vinculado. Se não for o seu usuário, favor entrar em contato com o NTI!");
                }
            ],
            'passwordValidate' => [
                'password',
                function ($attribute) {

                    if ($this->user !== null)
                        return;
                    
                    $ldap_con = ldap_connect("ldap.intranet.ufba.br");
                    $username = str_replace('@ufba.br','',$this->login);
                    if(!@ldap_bind($ldap_con, "$username@ufba.br", $this->password))
                        if(!@ldap_bind($ldap_con, "$username@intranet.ufba.br", $this->password))
                            $this->addError($attribute, 'Login e/ou senha incorreto!');
                    ldap_close($ldap_con);
                    
                }
            ]
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
        if ($this->validate() && !$this->user) {
            $session = Yii::$app->session;

                $user = new User();
                $user->username = $this->login;
                $user->email = "$user->username@ufba.br";
                $user->password_hash = "n";
                $user->auth_key = "n";
                $user->created_at = time();
                $user->updated_at = time();
           
                $aluno = SisaiAluno::findOne($this->id_aluno);

                $transaction = Yii::$app->db->beginTransaction();

                try{
                    if(!$user->save())
                        throw new Exception('Ocorreu um erro ao cadastrar o usuário, verifique se você digitou corretamente usuário/senha');
                    $aluno->id_user = $user->id;
                    if(!$aluno->save())
                        throw new Exception('Ocorreu um erro ao cadastrar o usuário, verifique se você digitou corretamente usuário/senha');
                    Yii::$app->db->createCommand()->insert('auth_assignment', [
                        'item_name' => 'Discente',
                        'user_id' => $user->id,
                        'created_at' => time()
                    ])->execute();
                    $this->user = $user;
                    $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                    throw new \yii\web\ServerErrorHttpException($e->getMessage(),500,$e);
                }
               
                Yii::$app->session->set('siscat_aluno', $aluno);
            
            
            $isLogged = Yii::$app->getUser()->login($this->user, 0);

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
