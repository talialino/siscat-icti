<?php

namespace app\modules\siscc\models;

use app\modules\sispit\models\SispitEnsinoComponente;
use app\modules\sispit\models\SispitMonitoria;
use Yii;

/**
 * This is the model class for table "siscc_componente_curricular".
 *
 * @property int $id_componente_curricular
 * @property string $nome
 * @property string $codigo_componente
 * @property int $ch_teorica
 * @property int $ch_pratica
 * @property int $ch_estagio
 * @property int $ch_extensao
 * @property int $modulo_teoria
 * @property int $modulo_pratica
 * @property int $modulo_estagio
 * @property int $modulo_extensao
 * @property int $modalidade
 * @property string $ementa
 * @property string $prerequisitos
 * @property int $ativo
 * @property int $anual
 *
 * @property SisaiAvaliacaoProfessorTurmaComponente[] $sisaiAvaliacaoProfessorTurmaComponentes
 * @property SisccProgramaComponenteCurricular[] $sisccProgramaComponenteCurriculars
 * @property SispitEnsinoComponente[] $sispitEnsinoComponentes
 * @property SispitMonitoria[] $sispitMonitorias
 */
class SisccComponenteCurricular extends \yii\db\ActiveRecord
{
    public const MODALIDADE = [
        'Disciplina' => [
            1 => 'Teórica',
            2 => 'Teórico-prática',
            3 => 'Teórico-prática em laboratório ou campo',
            4 => 'Teórica e prática com módulos diferenciados',
            22 => 'Prática',
        ],
        'Atividade' => [
            5 => 'Pesquisa',
            6 => 'Campo',
            7 => 'Laboratório',
            8 => 'ACCS',
            9 => 'Oficina',
            10 => 'Exposição',
            11 => 'Seminário',
        ],
        'Estágio' => [
            12 => 'Com acompanhamento individual ou em pequenos grupos',
            13 => 'De Licenciatura',
            14 => 'Em Equipe',
        ],
        'Trabalho de Conclusão de Curso (TCC)' => [
            15 => 'Orientação metodológica',
            16 => 'Com acompanhamento individual',
        ],
        'Componentes curriculares de curso de especialização em Residência na Àrea de Saúde' => [
            17 => 'Obrigatórios',
            18 => 'Optativos',
        ],
        'Atividade de Pós-graduação' => [
            19 => 'Tirocínio docente com acompanhamento',
            20 => 'Trabalho de conclusão de Residência na Área de Saúde com acompanhamento individual',
            21 => 'Pesquisa orientada, projeto de pesquisa, monografia, dissertação, ou tese, com acompanhamento individual',
        ],
    ];

    /**
     * Variavel virtual para armazenar os valores de $prerequisitos fornecidos pelo usuário,
     * no formulario da visão, antes de salvá-lo no banco de dados
     */
    public $prerequisitosInput;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siscc_componente_curricular';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'codigo_componente'], 'required'],
            [['ch_teorica', 'ch_pratica', 'ch_estagio','ch_extensao', 'modulo_teoria', 'modulo_pratica', 'modulo_estagio','modulo_extensao', 'modalidade', 'ativo', 'anual'], 'integer'],
            [['ementa', 'prerequisitos'], 'string'],
            [['nome'], 'string', 'max' => 255],
            [['codigo_componente'], 'string', 'max' => 7],
            [['prerequisitosInput'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'codigo_componente' => 'Código',
            'codigoNome' => 'Componente Curricular',
            'ch_teorica' => 'CH Teórica',
            'ch_pratica' => 'CH Prática',
            'ch_estagio' => 'CH Estágio',
            'ch_extensao' => 'CH Extensão',
            'modulo_teoria' => 'Módulo Teoria',
            'modulo_pratica' => 'Módulo Prática',
            'modulo_estagio' => 'Módulo Estágio',
            'modulo_extensao' => 'Módulo Extensão',
            'modalidade' => 'Modalidade / Submodalidade',
            'modalidadeSubmodalidade' => 'Modalidade / Submodalidade',
            'ementa' => 'Ementa',
            'prerequisitos' => 'Pré-requisito',
            'prerequisitosInput' => 'Pré-requisitos',
            'ativo' => 'Ativo',
            'anual' => 'Anual',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisccProgramaComponenteCurriculars()
    {
        return $this->hasMany(SisccProgramaComponenteCurricular::class, ['id_componente_curricular' => 'id_componente_curricular']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitEnsinoComponentes()
    {
        return $this->hasMany(SispitEnsinoComponente::class, ['id_componente_curricular' => 'id_componente_curricular']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitMonitorias()
    {
        return $this->hasMany(SispitMonitoria::class, ['id_componente_curricular' => 'id_componente_curricular']);
    }

    /**
     * Retorna o valor modalidade e a submodalidade por extenso
     * @return string
     */
    public function getModalidadeSubmodalidade()
    {
        foreach ($this::MODALIDADE as $modalidade => $submodalidade)
            foreach ($submodalidade as $k => $v)
                if($this->modalidade == $k)
                    return "$modalidade / $v";

        return '';
    }

    public function beforeValidate()
    {
        if(is_array($this->prerequisitosInput))
        {
            $temp = array();
            foreach($this->prerequisitosInput as $p)
            {
                if(!$p['colegiado'] && !trim($p['componente']))
                    continue;
                $temp[] = $p['colegiado'].'-'.$p['componente'];
            }
            if(count($temp) > 0)
                $this->prerequisitos = implode('|',$temp);
        }
        $this->codigo_componente = trim($this->codigo_componente);
        return parent::beforeValidate();
    }

    public function afterFind()
    {
        if($this->prerequisitos)
        {
            $temp = explode('|',$this->prerequisitos);
            
            foreach($temp as $t){
                $p = explode('-',$t);
                $this->prerequisitosInput[] = [
                    'colegiado' => $p[0],
                    'componente' => $p[1],
                ];
            }
        }
        $this->codigo_componente = trim($this->codigo_componente);
        parent::afterFind();
    }

    public function getCodigoNome()
    {
        return "$this->codigo_componente - $this->nome";
    }

    public function getCargaHorariaTotal() {
        return $this->ch_teorica + $this->ch_pratica + $this->ch_estagio + $this->ch_extensao;
    }
}
