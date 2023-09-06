<?php

namespace app\models;

use Yii;

/**
 * ContactForm is the model behind the contact form.
 */
class CGDPContactForm extends ContactForm
{
    public $verifyCode;
    public $subject = 'Solicitação de Acesso ao SISCAT';
    public $body = 'Venho por meio deste, solicitar liberação de acesso ao SISCAT, conforme dados abaixo. (Essa mensagem foi gerada pelo SISCAT)';
    public $siape;
    public $loginUfba;
    public $telefones;

    public function getTitulo()
    {
        return $this->subject;
    }

    public function getMensagemInicial()
    {
        return 'Para solicitar a sua liberação no SISCAT, você precisa preencher as informações abaixo.<br/>
        Essa solicitação será encaminhada à Coordenação de Gestão e Desenvolvimento de Pessoas para que eles possam fazer a sua liberação.';
    }

    public function getMensagemResposta()
    {
        return 'Agradecemos pelo seu contato. Sua solicitação foi encaminhada para a CGDP que entrará em contato contigo quando liberar seu acesso.';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['siape', 'loginUfba', 'telefones', 'email', 'name'], 'required'],
        ]);
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'siape' => 'Matrícula SIAPE',
            'email' => 'Email para Contato',
            'loginUfba' => 'Usuário UFBA',
        ]);
    }

    public function contact($email = 'cgdp.ims@ufba.br')
    {
        $this->body .= "<br/>
        <br/>Nome Completo: $this->name
        <br/>Matrícula SIAPE: $this->siape
        <br/>Usuário UFBA: $this->loginUfba
        <br/>Telefones: $this->telefones";

        return parent::contact($email);
    }
}
