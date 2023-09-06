<?php

namespace app\modules\sisai\models;

use app\modules\siscc\models\SisccSemestre;
use app\modules\sisrh\models\SisrhPessoa;
use Yii;

/**
 * This is the model class for table "sisai_avaliacao".
 *
 * @property int $id_avaliacao
 * @property int $id_semestre
 * @property int $id_aluno
 * @property int $id_pessoa
 * @property int $tipo_avaliacao
 * @property int $situacao
 *
 * @property SisaiAlunoRespostaObjetiva[] $sisaiAlunoRespostaObjetivas
 * @property SisaiAlunoRespostaSubjetiva[] $sisaiAlunoRespostaSubjetivas
 * @property SisaiAluno $aluno
 * @property SisccSemestre $semestre
 * @property SisrhPessoa $pessoa
 * @property SisaiProfessorRespostaObjetiva[] $sisaiProfessorRespostaObjetivas
 * @property SisaiProfessorRespostaSubjetiva[] $sisaiProfessorRespostaSubjetivas
 * @property SisaiTecnicoRespostaObjetiva[] $sisaiTecnicoRespostaObjetivas
 * @property SisaiTecnicoRespostaSubjetiva[] $sisaiTecnicoRespostaSubjetivas
 * @property SisaiAlunoComponenteCurricular[] $sisaiAlunoComponenteCurriculares
 * @property SisaiProfessorComponenteCurricular[] $sisaiProfessorComponenteCurriculares
 */
class SisaiAvaliacao extends \yii\db\ActiveRecord
{
    public const TIPO_AVALIACAO = [
        0 => 'Discente',
        1 => 'Docente',
        2 => 'Técnico',
    ];

    //As três constantes a seguir mostram os tipos de questionário e a ordem que cada seguimento irá responder
    //Esse tipo de questionário está especificado no objeto SisaiPeríodoAvaliacao contendo a avaliação atual
    public const QUESTIONARIOS_DISCENTE = [1 => 1, 2 => 2, 3 => 7, 4 => 17, 5 => 10, 6 => 11, 7 => 13, 8 => 15];
    public const QUESTIONARIOS_DOCENTE = [1 => 3, 2 => 4, 3 => 8, 4 => 9, 5 => 10, 6 => 12, 7 => 18, 8 => 13, 9 => 14, 10 => 15];
    public const QUESTIONARIOS_TECNICO = [1 => 19, 2 => 10, 3 => 13, 4 => 14, 5 => 15];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_avaliacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_semestre'], 'required'],
            [['id_semestre', 'id_aluno', 'id_pessoa', 'tipo_avaliacao', 'situacao'], 'integer'],
            [['id_aluno'], 'exist', 'skipOnError' => true, 'targetClass' => SisaiAluno::class, 'targetAttribute' => ['id_aluno' => 'id_aluno']],
            [['id_semestre'], 'exist', 'skipOnError' => true, 'targetClass' => SisccSemestre::class, 'targetAttribute' => ['id_semestre' => 'id_semestre']],
            [['id_pessoa'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhPessoa::class, 'targetAttribute' => ['id_pessoa' => 'id_pessoa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_avaliacao' => 'Avaliacao',
            'id_semestre' => 'Semestre',
            'semestre.string' => 'Semestre',
            'id_aluno' => 'Aluno',
            'id_pessoa' => 'Pessoa',
            'tipo_avaliacao' => 'Tipo de Avaliação',
            'tipoAvaliacao' => 'Tipo de Avaliação',
            'situacao' => 'Situação',
            'situacaoString' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiAlunoRespostaObjetivas()
    {
        return $this->hasMany(SisaiAlunoRespostaObjetiva::class, ['id_avaliacao' => 'id_avaliacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiAlunoRespostaSubjetivas()
    {
        return $this->hasMany(SisaiAlunoRespostaSubjetiva::class, ['id_avaliacao' => 'id_avaliacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAluno()
    {
        return $this->hasOne(SisaiAluno::class, ['id_aluno' => 'id_aluno']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemestre()
    {
        return $this->hasOne(SisccSemestre::class, ['id_semestre' => 'id_semestre']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoa()
    {
        return $this->hasOne(SisrhPessoa::class, ['id_pessoa' => 'id_pessoa']);
    }

    public function getAvaliador()
    {
        if($this->id_aluno != null)
            return $this->getAluno();
        return $this->getPessoa();
    }

    public function getSituacaoString()
    {
        switch($this->situacao)
        {
            case 0:
                return 'Iniciada';
            case 99:
                return 'Concluída';
            case 98://Avaliação de novos componentes adicionados pelo aluno após conclusão das outras etapas
                return SisaiQuestionario::TIPO_QUESTIONARIO[2];
            case 97://Avaliação de novas turmas adicionadas pelo professor após conclusão das outras etapas
                return SisaiQuestionario::TIPO_QUESTIONARIO[4];
            default:
                return SisaiQuestionario::TIPO_QUESTIONARIO[$this->situacao];
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiProfessorRespostaObjetivas()
    {
        return $this->hasMany(SisaiProfessorRespostaObjetiva::class, ['id_avaliacao' => 'id_avaliacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiProfessorRespostaSubjetivas()
    {
        return $this->hasMany(SisaiProfessorRespostaSubjetiva::class, ['id_avaliacao' => 'id_avaliacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiTecnicoRespostaObjetivas()
    {
        return $this->hasMany(SisaiTecnicoRespostaObjetiva::class, ['id_avaliacao' => 'id_avaliacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiTecnicoRespostaSubjetivas()
    {
        return $this->hasMany(SisaiTecnicoRespostaSubjetiva::class, ['id_avaliacao' => 'id_avaliacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiAlunoComponenteCurriculares()
    {
        return $this->hasMany(SisaiAlunoComponenteCurricular::class, ['id_avaliacao' => 'id_avaliacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiProfessorComponenteCurriculares()
    {
        return $this->hasMany(SisaiProfessorComponenteCurricular::class, ['id_avaliacao' => 'id_avaliacao']);
    }

    public function getTipoAvaliacao()
    {
        if($this->tipo_avaliacao !== null)
            return self::TIPO_AVALIACAO[$this->tipo_avaliacao];
        return false;
    }

    public function getQuestionarioForm()
    {
        $periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao');
        $alunoComponenteCurricular = null;
        $professorComponenteCurricular = null;
        $parametros = array();
        if($this->tipo_avaliacao == 0 && $this->situacao == 2 || $this->situacao == 98)
        {
            $alunoComponenteCurricular =
                $this->getSisaiAlunoComponenteCurriculares()->where(['concluida' => 0])->orderBy('id_aluno_componente_curricular')->one();

            if($alunoComponenteCurricular == null)
                return null;
            $parametros['componente'] = $alunoComponenteCurricular->id_componente_curricular;
            $parametros['colegiado'] = $alunoComponenteCurricular->id_setor;
            $parametros['docente'] = $alunoComponenteCurricular->id_pessoa;
        }
        if($this->tipo_avaliacao == 1 && $this->situacao == 4 || $this->situacao == 97)
        {
            $professorComponenteCurricular =
                $this->getSisaiProfessorComponenteCurriculares()->where(['concluida' => 0])->orderBy('id_professor_componente_curricular')->one();
            if($professorComponenteCurricular == null)
                return null;
            $parametros['componente'] = $professorComponenteCurricular->id_componente_curricular;
        }
        $idQuestionario = $periodoAvaliacao->getIdQuestionario($this->situacao,$parametros);
        return new QuestionarioForm($idQuestionario, ['avaliacao' => $this,
            'alunoComponenteCurricular' => $alunoComponenteCurricular, 'professorComponenteCurricular' => $professorComponenteCurricular]);
    }

    /**
     * Atualiza a situação da avaliação com base no valor atual e no tipo de avaliação
     * @return bool
     */
    public function atualizarSituacao($pularAvaliacaoColegiado = false)
    {
        switch($this->tipo_avaliacao)
        {
            case 0:
                if($this->situacao == 2 || $this->situacao == 98)
                    if($this->getSisaiAlunoComponenteCurriculares()->where(['concluida' => 0])->exists())
                        return true;
                $indice = array_search($this->situacao,self::QUESTIONARIOS_DISCENTE);
                if(!$indice || $indice == count(self::QUESTIONARIOS_DISCENTE))
                    $this->situacao = 99;
                else
                    $this->situacao = self::QUESTIONARIOS_DISCENTE[$indice + 1];
                
                return $this->save();
            case 1:
                if($this->situacao == 4 || $this->situacao == 97)
                    if($this->getSisaiProfessorComponenteCurriculares()->where(['concluida' => 0])->exists())
                        return true;
                if($this->situacao == 18 && !$pularAvaliacaoColegiado)
                    return true;
                $indice = array_search($this->situacao,self::QUESTIONARIOS_DOCENTE);
                if(!$indice || $indice == count(self::QUESTIONARIOS_DOCENTE))
                    $this->situacao = 99;
                else
                    $this->situacao = self::QUESTIONARIOS_DOCENTE[$indice + 1];
                
                return $this->save();
            case 2:
                $indice = array_search($this->situacao,self::QUESTIONARIOS_TECNICO);
                if($indice == count(self::QUESTIONARIOS_TECNICO))
                    $this->situacao = 99;
                else
                    $this->situacao = self::QUESTIONARIOS_TECNICO[$indice + 1];
                
                return $this->save();
        }
        return false;
    }

    /**
     * Retorna um array contendo a etapa atual e o total de etapas
     * @return array
     */
    public function getEtapas()
    {
        $atual = 0;
        $total = 0;
        switch($this->tipo_avaliacao)
        {
            case 0:
                $total = count(self::QUESTIONARIOS_DISCENTE);
                if($this->situacao < 20)
                    $atual = array_search($this->situacao,self::QUESTIONARIOS_DISCENTE);
                else
                    $atual = $total;
                $total += count($this->sisaiAlunoComponenteCurriculares) - 1;
                $concluidos = 0;
                foreach($this->sisaiAlunoComponenteCurriculares as $componente){
                    if($componente->concluida)
                        $concluidos++;
                }
                if($atual == 2 || $this->situacao == 98)
                    $atual += $concluidos;
                else if($atual > 2)
                    $atual += $concluidos - 1;
            break;
            case 1:
                $total = count(self::QUESTIONARIOS_DOCENTE);
                if($this->situacao < 20)
                    $atual = array_search($this->situacao,self::QUESTIONARIOS_DOCENTE);
                else
                    $atual = $total;
                $total += count($this->sisaiProfessorComponenteCurriculares) - 1;
                $concluidos = 0;
                foreach($this->sisaiProfessorComponenteCurriculares as $componente){
                    if($componente->concluida)
                        $concluidos++;
                }
                if($atual == 2 || $this->situacao == 97)
                    $atual += $concluidos;
                else if($atual > 2)
                    $atual += $concluidos - 1;
            break;
            case 2:
                $total = count(self::QUESTIONARIOS_TECNICO);
                $atual = array_search($this->situacao,self::QUESTIONARIOS_TECNICO);
        }
        return [$atual,$total];
    }

    /**
     * Busca a avaliação atual para o semestre do período de avaliação vigente. Caso ainda não tenha sido criado, cria um novo objeto e salva no BD
     * Retorna false se não houver nenhum período de avaliação e null se não for possível criar um registro de SisaiAvaliacao
     * @param bool $aluno
     * @return mixed
     */
    public static function avaliacaoAtual($tipo_avaliacao)
    {
        $periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao', false);
        if(!$periodoAvaliacao)
            return false;
        $id = $tipo_avaliacao == 0 ? SisaiAluno::usuarioAtivo(Yii::$app->user->id)->id_aluno : SisrhPessoa::usuarioAtivo(Yii::$app->user->id)->id_pessoa;
        $atributo = $tipo_avaliacao == 0 ? 'id_aluno' : 'id_pessoa';
        $avaliacao = SisaiAvaliacao::find()->where(['id_semestre' => $periodoAvaliacao->id_semestre,
            $atributo => $id, 'tipo_avaliacao' => $tipo_avaliacao])->one();
        if($avaliacao == null)
        {
            $avaliacao = new SisaiAvaliacao();
            $avaliacao->id_semestre = $periodoAvaliacao->id_semestre;
            $avaliacao->$atributo = $id;
            $avaliacao->tipo_avaliacao = $tipo_avaliacao;
            $avaliacao->situacao = 0;
            if(!$avaliacao->save())
                return null;
        }
        return $avaliacao;
    }
}
