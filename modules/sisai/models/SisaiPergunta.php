<?php

namespace app\modules\sisai\models;

use Yii;

/**
 * This is the model class for table "sisai_pergunta".
 *
 * @property int $id_pergunta
 * @property string $descricao
 * @property int $tipo_pergunta
 * @property int $id_grupo_perguntas
 * @property int $nsa 1 - Não se aplica; 2 - Não se aplica/não sabe opinar; 3 - Não se aplica - supervisão direta; 4 - Não se aplica - supervisão indireta; 5 - Desconheço
 *
 * @property SisaiAlunoRespostaObjetiva[] $sisaiAlunoRespostaObjetivas
 * @property SisaiAlunoRespostaSubjetiva[] $sisaiAlunoRespostaSubjetivas
 * @property SisaiGrupoPerguntas $grupoPerguntas
 * @property SisaiProfessorRespostaObjetiva[] $sisaiProfessorRespostaObjetivas
 * @property SisaiProfessorRespostaSubjetiva[] $sisaiProfessorRespostaSubjetivas
 * @property SisaiRespostaMultiplaEscolha[] $sisaiRespostaMultiplaEscolhas
 * @property SisaiTecnicoRespostaObjetiva[] $sisaiTecnicoRespostaObjetivas
 * @property SisaiTecnicoRespostaSubjetiva[] $sisaiTecnicoRespostaSubjetivas
 */
class SisaiPergunta extends \yii\db\ActiveRecord
{
    /**
     * Constantes que definem o tipo da questão.
     */
    public const PADRAO = 1;
    public const OBJETIVA = 2;
    public const MULTIPLA_ESCOLHA = 3;
    public const ABERTA = 4;
    public const COLEGIADO = 5;

    public const TIPO_PERGUNTA = [
        self::PADRAO => 'Padrão',
        self::OBJETIVA => 'Objetiva',
        self::MULTIPLA_ESCOLHA => 'Múltipla Escolha',
        self::ABERTA => 'Aberta',
        self::COLEGIADO => 'Colegiado',
    ];

    public const ALTERNATIVAS_PADRAO = [
        5 => 'Plenamente satisfatório',
        4 => 'Satisfatório',
        3 => 'Regular',
        2 => 'Pouco satisfatório',
        1 => 'Insatisfatório',
    ];

    public const NAO_SE_APLICA = [
        0 => '',
        1 => 'Não se aplica',
        2 => 'Não se aplica/não sabe opinar',
        3 => 'Não se aplica - supervisão direta',
        4 => 'Não se aplica - supervisão indireta',
        5 => 'Desconheço',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_pergunta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao'], 'string'],
            [['tipo_pergunta', 'id_grupo_perguntas', 'nsa'], 'integer'],
            [['id_grupo_perguntas'], 'required'],
            [['id_grupo_perguntas'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiGrupoPerguntas::class, 'targetAttribute' => ['id_grupo_perguntas' => 'id_grupo_perguntas']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pergunta' => 'Id Pergunta',
            'descricao' => 'Descrição',
            'tipo_pergunta' => 'Tipo de Pergunta',
            'id_grupo_perguntas' => 'Grupo de Perguntas',
            'questionario' => 'Questionário',
            'nsa' => 'Opção Não se aplica',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiAlunoRespostaObjetivas()
    {
        return $this->hasMany(SisaiAlunoRespostaObjetiva::class, ['id_pergunta' => 'id_pergunta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiAlunoRespostaSubjetivas()
    {
        return $this->hasMany(SisaiAlunoRespostaSubjetiva::class, ['id_pergunta' => 'id_pergunta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoPerguntas()
    {
        return $this->hasOne(SisaiGrupoPerguntas::class, ['id_grupo_perguntas' => 'id_grupo_perguntas']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionario()
    {
        return $this->hasOne(SisaiQuestionario::class, ['id_questionario' => 'id_questionario'])->via('grupoPerguntas');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiProfessorRespostaObjetivas()
    {
        return $this->hasMany(SisaiProfessorRespostaObjetiva::class, ['id_pergunta' => 'id_pergunta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiProfessorRespostaSubjetivas()
    {
        return $this->hasMany(SisaiProfessorRespostaSubjetiva::class, ['id_pergunta' => 'id_pergunta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiRespostaMultiplaEscolhas()
    {
        return $this->hasMany(SisaiRespostaMultiplaEscolha::class, ['id_pergunta' => 'id_pergunta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiTecnicoRespostaObjetivas()
    {
        return $this->hasMany(SisaiTecnicoRespostaObjetiva::class, ['id_pergunta' => 'id_pergunta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiTecnicoRespostaSubjetivas()
    {
        return $this->hasMany(SisaiTecnicoRespostaSubjetiva::class, ['id_pergunta' => 'id_pergunta']);
    }

    /**
     * @return array
     */
    public function getAlternativas()
    {
        $retorno = null;
        switch($this->tipo_pergunta){
            case self::PADRAO:
                $retorno = self::ALTERNATIVAS_PADRAO;
                break;
            case self::OBJETIVA:
            case self::MULTIPLA_ESCOLHA:
                $retorno = array();
                foreach($this->sisaiRespostaMultiplaEscolhas as $alternativas){
                    $retorno[$alternativas->id_sisai_resposta_multipla_escolha] = $alternativas->descricao;
                }
            break;
        }
        if($this->nsa > 0)
            $retorno[0] = self::NAO_SE_APLICA[$this->nsa];
        return $retorno;
    }

    public function getTipoPergunta()
    {
        if($this->tipo_pergunta == null)
            return null;
        return self::TIPO_PERGUNTA[$this->tipo_pergunta];
    }
}
