<?php

namespace app\modules\sisai\models;

use app\modules\siscc\models\SisccSemestre;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "sisai_periodo_avaliacao".
 *
 * @property int $id_semestre
 * @property string $data_inicio
 * @property string $data_fim
 * @property string $questionarios
 * @property string $componentes_estagio
 * @property string $componentes_online
 * 
 * @property SisccSemestre $semestre
 */
class SisaiPeriodoAvaliacao extends \yii\db\ActiveRecord
{

    /**
     * Esta constante armazena os ids dos questionários padrões para cada tipo de avaliação.
     * É importante pois caso haja modificações nos instrumentos de avaliação, esse valor deve ser alterado para
     * que as próximas avaliações sejam ligadas aos questionários corretos.
     */
    public const DEFAULT_QUESTIONARIOS = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_periodo_avaliacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_semestre','data_inicio', 'data_fim'], 'required'],
            [['id_semestre'], 'integer'],
            [['data_inicio', 'data_fim', 'componentes_estagio', 'componentes_online'], 'safe'],
            [['questionarios'], 'string'],
            [['id_semestre'], 'exist', 'skipOnError' => true, 'targetClass' => SisccSemestre::class, 'targetAttribute' => ['id_semestre' => 'id_semestre']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_semestre' => 'Semestre',
            'semestre.string' => 'Semestre',
            'data_inicio' => 'Data Início',
            'data_fim' => 'Data Fim',
            'questionarios' => 'Questionários',
            'componentes_estagio' => 'Componentes Curriculares de  Estágio da Graduação',
            'componentes_online' => 'Componentes Ofertados no Modo Online',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemestre()
    {
        return $this->hasOne(SisccSemestre::class, ['id_semestre' => 'id_semestre']);
    }

    public function beforeValidate()
    {
        if(!is_array($this->questionarios))
            return false;
        $this->questionarios = serialize($this->questionarios);
        
        if(!is_array($this->componentes_estagio))
            return false;
        $this->componentes_estagio = serialize($this->componentes_estagio);
        
        if(!is_array($this->componentes_online))
            return false;
        $this->componentes_online = serialize($this->componentes_online);

        return parent::beforeValidate();
    }

    public function afterFind()
    {
        parent::afterFind();
        if($this->questionarios != null)
            $this->questionarios = unserialize($this->questionarios);
        if($this->componentes_estagio != null)
            $this->componentes_estagio = unserialize($this->componentes_estagio);
        if($this->componentes_online != null)
            $this->componentes_online = unserialize($this->componentes_online);
        
    }
    public static function periodoAtivo()
    {
        return self::find()->where(new Expression('NOW() BETWEEN data_inicio AND data_fim'))->one();
    }

    public function getIdQuestionario($indice, $parametros = array())
    {
        switch($indice)
        {
            case 98:
                $indice = 2;
            break;
            case 97:
                $indice = 4;
            break;
        }
        if($indice == 2)
        {
            foreach((array)$this->componentes_estagio as $id_componente)
                if($parametros['componente'] == $id_componente)
                    return $this->questionarios[5];
            foreach((array)$this->componentes_online as $componente)
                    if($parametros['componente'] == $componente['componente'] &&
                        $parametros['colegiado'] == $componente['colegiado'] &&
                        $parametros['docente'] == $componente['docente'])
                        return $this->questionarios[16];
        }
        if($indice == 4)
        {
            if(count($parametros) > 0)
                foreach((array)$this->componentes_estagio as $id_componente)
                    if($parametros['componente'] == $id_componente)
                        return $this->questionarios[6];
        }

        return $this->questionarios[$indice];
    }
}
