<?php

namespace app\modules\sisai\models;

use app\modules\sisai\helpers\RelatorioSistemasAntigos;
use app\modules\siscc\models\SisccComponenteCurricular;
use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sisrh\models\SisrhSetor;
use Yii;
use yii\base\Model;
use yii\db\Query;

class RelatorioForm extends Model{
    
    public const SCENARIO_DOCENTE = 'Avaliacao Docente';
    public const SCENARIO_DISCENTE = 'Avaliacao Discente';

    public $id_pessoa;
    public $id_componente_curricular;
    public $id_setor;
    public $id_semestre;
    public $componente_colegiado;

    public function attributeLabels()
    {
        return [
            'id_semestre' => 'Semestre',
            'id_componente_curricular' => 'Componente Curricular',
            'componente_colegiado' => 'Componente Curricular',
            'id_setor' => 'Colegiado/Núcleo',
            'id_pessoa' => 'Docente',
        ];
    }

    public function rules()
    {
        return [
            [['id_semestre','componente_colegiado','id_pessoa'], 'required', 'on' => self::SCENARIO_DOCENTE],
            [['id_semestre','id_setor'], 'required', 'on' => self::SCENARIO_DISCENTE],
            [['id_semestre', 'id_pessoa','id_componente_curricular','id_setor'], 'integer'],
        ];
    }

    public function getPessoa()
    {
        if($this->id_pessoa)
            return SisrhPessoa::findOne($this->id_pessoa)->nome;
        return null;
    }

    public function getColegiado()
    {
        if($this->id_setor)
            return SisrhSetor::findOne($this->id_setor)->colegiado;
        return null;
    }

    public function getSetor()
    {
        if($this->id_setor)
            return SisrhSetor::findOne($this->id_setor)->nome;
        return null;
    }

    public function getSemestre()
    {
        if($this->id_semestre)
            return SisccSemestre::findOne($this->id_semestre)->string;
        return null;
    }

    public function getComponenteCurricular()
    {
        if($this->id_componente_curricular)
            return SisccComponenteCurricular::findOne($this->id_componente_curricular)->codigonome;
        return null;
    }

    public function getComponenteColegiado()
    {
        if($this->componente_colegiado)
        {
            $cc = explode('-',$this->componente_colegiado);
            if($this->id_semestre < 23)
                return SisccComponenteCurricular::findOne($cc[0])->codigonome;
            return SisccComponenteCurricular::findOne($cc[0])->codigonome.' - '.SisrhSetor::findOne($cc[1])->colegiado;
        }
        return null;
    }

    public function beforeValidate()
    {
        $periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao', false);
        if($periodoAvaliacao && $periodoAvaliacao->id_semestre == $this->id_semestre)
            $this->addError('id_semestre','Não é possível ver relatório de semestre com período de avaliação vigente.');
        return parent::beforeValidate();
    }

    public function listaComponentesColegiados()
    {
        if($this->id_pessoa == null || $this->id_semestre == null)
            return array();
        
        if($this->id_semestre < 23)
            return RelatorioSistemasAntigos::listaComponentesColegiados($this->id_pessoa,$this->id_semestre);

        $query = new Query();
        $query->select('c.id_componente_curricular, c.codigo_componente, c.nome as componente, s.id_setor, s.nome as colegiado')
            ->from('siscc_componente_curricular c, sisrh_setor s,
                siscc_programa_componente_curricular p, siscc_programa_componente_curricular_pessoa pp')
            ->where('c.id_componente_curricular = p.id_componente_curricular AND s.id_setor = p.id_setor
                AND p.id_semestre=:semestre AND p.id_programa_componente_curricular = pp.id_programa_componente_curricular
                AND pp.id_pessoa =:pessoa',[':semestre' => $this->id_semestre, ':pessoa' => $this->id_pessoa]);
        $resultados = $query->all();
        $saida = array();
        foreach($resultados as $linha)
        {
            $colegiado = SisrhSetor::ajustarNomeColegiado($linha['colegiado']);
            $saida["{$linha['id_componente_curricular']}-{$linha['id_setor']}"] = 
                "{$linha['codigo_componente']} - {$linha['componente']} - {$colegiado}";
        }
        return $saida;
    }
}