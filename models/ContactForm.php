<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
abstract class ContactForm extends Model
{
    public $verifyCode;
    public $name;
    public $email;
    public $subject;
    public $body;

    abstract public function getTitulo();
    abstract public function getMensagemInicial();
    abstract public function getMensagemResposta();

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
            ['email','email'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'subject' => 'Assunto',
            'name' => 'Nome Completo',
            'body' => 'Mensagem',
            'verifyCode' => 'Código de Verificação',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose('siscat_contact',['mensagem' => $this->body])
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->send();

            return true;
        }
        return false;
    }
}
