<?php

namespace app\modules\sisape\models;

use Exception;
use Throwable;
use Yii;

/**
 * Este modelo serve para gerar um formulário que permita adicionar integrantes a um projeto
 * e ao mesmo tempo permita criar um integrante externo caso seja necessário.message
 * Ele reuni os propriedades das duas classes: SisapeProjetoIntegrante e SisapeIntegranteExterno
 */
class ProjetoIntegranteForm extends \yii\base\Model
{
    public const TIPOS = [
        1 => 'Discente UFBA',
        2 => 'Servidor UFBA (docente ou técnico)',
        3 => 'Outro',
    ];
    /**
     * Variáveis de ProjetoIntegrante
     */
    public $tipoIntegrante;
    public $id_projeto;
    public $id_integrante_externo;
    public $id_pessoa;
    public $funcao;
    public $id_aluno;
    public $carga_horaria;
    public $vinculo;
    /**
     * Variáveis de IntegranteExterno
     */
    public $nome;
    public $email;
    public $telefone;

    public function rules()
    {
        return [
            [['tipoIntegrante', 'id_projeto', 'carga_horaria', 'funcao'], 'required'],
            [['id_pessoa', 'id_integrante_externo', 'id_aluno', 'tipoIntegrante', 'carga_horaria', 'vinculo'], 'integer'],
            [['funcao', 'nome', 'email', 'telefone'], 'string'],
            [['nome', 'email', 'vinculo'], 'required', 'when' => function($model) {
                    return $model->tipoIntegrante == 3;
                }, 'whenClient' => "function (attribute, value) {
                    return $('#tipoIntegrante').val() == 3;
                }"
            ],
            [['id_aluno','vinculo'], 'required', 'when' => function($model) {
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
            'funcao' => 'Função no Projeto',
            'carga_horaria' => 'Carga Horária Semanal',
            'vinculo' => 'Vínculo',
        ];
    }

    public function save($projetoIntegrante = false)
    {
        if($projetoIntegrante == false)
            $projetoIntegrante = new SisapeProjetoIntegrante();
        $projetoIntegrante->id_projeto = $this->id_projeto;
        $projetoIntegrante->funcao = $this->funcao;
        $projetoIntegrante->carga_horaria = $this->carga_horaria;

        $transaction = Yii::$app->db->beginTransaction();

        try{
            switch($this->tipoIntegrante){
                case 1:
                    $projetoIntegrante->id_aluno = $this->id_aluno;
                    $projetoIntegrante->vinculo = $this->vinculo;
                break;
                case 2:
                    $projetoIntegrante->id_pessoa = $this->id_pessoa;
                break;
                case 3:
                    
                    $integrante = $projetoIntegrante->getIsNewRecord() ? new SisapeIntegranteExterno() :
                        SisapeIntegranteExterno::findOne(['id_integrante_externo' => $this->id_integrante_externo]);
                    $integrante->setAttributes($this->attributes);
                    if(!$integrante->save())
                    {
                        $erros = $integrante->getFirstErrors();
                        foreach($erros as $key => $value)
                        {
                            $projetoIntegrante->addError('id_integrante_externo',$value);
                        }
                        throw new Exception('Erro ao salvar integrante externo');
                    }
                    $this->id_integrante_externo = $integrante->id_integrante_externo;
                    $projetoIntegrante->id_integrante_externo = $this->id_integrante_externo;
                    $projetoIntegrante->vinculo = $this->vinculo;
            }
        }catch(Throwable $e)
        {
            $transaction->rollBack();
            return false;
        }
        if($projetoIntegrante->save())
        {
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        return false;
    }

    public function carregarProjetoIntegrante($projetoIntegrante)
    {
        $this->setAttributes($projetoIntegrante->attributes);
        $this->tipoIntegrante = $this->id_aluno != null ? 1 :
            ($this->id_pessoa != null ? 2 : 3);
        if($this->tipoIntegrante == 3)
            $this->setAttributes(SisapeIntegranteExterno::findOne(['id_integrante_externo' => $this->id_integrante_externo])->attributes);
    }
}