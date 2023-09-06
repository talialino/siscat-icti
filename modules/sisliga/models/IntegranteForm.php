<?php

namespace app\modules\sisliga\models;

use Yii;

/**
 * Este modelo serve para gerar um formulário que permita adicionar integrantes a um projeto
 * e ao mesmo tempo permita criar um integrante externo caso seja necessário.message
 * Ele reuni os propriedades das duas classes: SisligaLigaIntegrante e SisligaIntegranteExterno
 */
class IntegranteForm extends \yii\base\Model
{
    public const TIPOS = [
        1 => 'Discente UFBA',
        2 => 'Servidor UFBA (docente ou técnico)',
        3 => 'Outro',
    ];
    /**
     * Variáveis de SisligaLigaIntegrante
     */
    public $tipoIntegrante;
    public $id_liga_academica;
    public $id_integrante_externo;
    public $id_pessoa;
    public $funcao;
    public $id_aluno;
    public $carga_horaria;
    /**
     * Variáveis de IntegranteExterno
     */
    public $nome;
    public $email;
    public $telefone;
    public $instituicao;

    public function rules()
    {
        return [
            [['tipoIntegrante', 'id_liga_academica', 'carga_horaria', 'funcao'], 'required'],
            [['id_pessoa', 'id_integrante_externo', 'id_aluno', 'tipoIntegrante', 'carga_horaria'], 'integer'],
            [['funcao', 'nome', 'email', 'telefone', 'instituicao'], 'string'],
            [['nome', 'email', 'instituicao'], 'required', 'when' => function($model) {
                    return $model->tipoIntegrante == 3;
                }, 'whenClient' => "function (attribute, value) {
                    return $('#tipoIntegrante').val() == 3;
                }"
            ],
            [['id_aluno'], 'required', 'when' => function($model) {
                    return $model->tipoIntegrante == 1;
                }, 'whenClient' => "function (attribute, value) {
                    return $('#tipoIntegrante').val() == 1;
                }"
            ],
            [['id_pessoa'], 'required', 'when' => function($model) {
                    return $model->tipoIntegrante == 2;
                }, 'whenClient' => "function (attribute, value) {
                    return $('#tipoIntegrante').val() == 2;
                }"
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            //'id_integrante_externo' => 'Integrante',
            'id_pessoa' => 'Servidor',
            'id_aluno' => 'Discente',
            'tipoIntegrante' => 'Tipo de Integrante',
            'funcao' => 'Função',
            'carga_horaria' => 'Carga Horária',
            'instituicao' => 'Instituição',
        ];
    }

    public function save($ligaIntegrante = false)
    {
        if($ligaIntegrante == false)
            $ligaIntegrante = new SisligaLigaIntegrante();
        $ligaIntegrante->id_liga_academica = $this->id_liga_academica;
        $ligaIntegrante->funcao = $this->funcao;
        $ligaIntegrante->carga_horaria = $this->carga_horaria;

        switch($this->tipoIntegrante){
            case 1:
                $ligaIntegrante->id_aluno = $this->id_aluno;
            break;
            case 2:
                $ligaIntegrante->id_pessoa = $this->id_pessoa;
            break;
            case 3:
                
                $integrante = $ligaIntegrante->getIsNewRecord() ? new SisligaIntegranteExterno() :
                    SisligaIntegranteExterno::findOne(['id_integrante_externo' => $this->id_integrante_externo]);
                $integrante->setAttributes($this->attributes);
                if(!$integrante->save())
                    return false;
                $this->id_integrante_externo = $integrante->id_integrante_externo;
                $ligaIntegrante->id_integrante_externo = $this->id_integrante_externo;
        }

        return $ligaIntegrante->save();
    }

    public function carregarLigaIntegrante($ligaIntegrante)
    {
        $this->setAttributes($ligaIntegrante->attributes);
        $this->tipoIntegrante = $this->id_aluno != null ? 1 :
            ($this->id_pessoa != null ? 2 : 3);
        if($this->tipoIntegrante == 3)
            $this->setAttributes(SisligaIntegranteExterno::findOne(['id_integrante_externo' => $this->id_integrante_externo])->attributes);
    }
}