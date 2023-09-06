<?php

namespace app\models;

use Yii;

/**
 * ContactForm is the model behind the contact form.
 */
class ErrorContactForm extends ContactForm
{
    public $verifyCode;
    public $subject = 'Suporte SISCAT';
    public $loginUfba;
    public $mensagemErro;

    public function getTitulo()
    {
        return $this->subject;
    }

    public function getMensagemInicial()
    {
        return 'Por favor, nos informe mais detalhes para que possamos verificar o problema.';
    }

    public function getMensagemResposta()
    {
        return 'Agradecemos pelo seu contato. Sua solicitação foi encaminhada ao NTI para providências.';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['loginUfba', 'mensagemErro', 'email', 'name'], 'required'],
        ]);
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'email' => 'Email para Contato',
            'loginUfba' => 'Usuário UFBA',
            'body' => 'Forneça uma breve descrição de qual operação você estava tentando executar',
        ]);
    }

    public function contact($email)
    {
        $this->body .= "<br/>
        <br/>Nome Completo: $this->name
        <br/>Usuário UFBA: $this->loginUfba
        <br/>Mensagem de Erro: $this->mensagemErro";

        return parent::contact($email);
    }
}
