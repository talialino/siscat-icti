<?php

namespace app\modules\sisai\models;

use app\modules\sispit\models\SispitEnsinoOrientacao;
use app\modules\sispit\models\SispitMonitoria;
use app\modules\sispit\models\SispitOrientacaoAcademica;
use Yii;
use app\modules\sisrh\models\SisrhSetor;
use app\modules\siscc\models\SisccSemestre;
use dektrium\user\models\User;
use Exception;

/**
 * This is the model class for table "sisai_aluno".
 *
 * @property int $id_aluno
 * @property string $nome
 * @property int $matricula
 * @property string $email
 * @property int $id_setor
 * @property int $ativo
 * @property int $id_user
 * @property int $id_semestre
 * @property int $nivel_escolaridade
 *
 * @property SisrhSetor $setor
 * @property SisaiAvaliacao[] $sisaiAvaliacaos
 * @property SispitEnsOrientacao[] $sispitEnsOrientacaos
 * @property SispitMonitoria[] $sispitMonitorias
 * @property SispitOrientacaoAcademica[] $sispitOrientacaoAcademicas
 */
class SisaiAluno extends \yii\db\ActiveRecord
{
    public const ESCOLARIDADE = [
        1 => 'Graduação',
        2 => 'Especialização',
        3 => 'Mestrado',
        4 => 'Doutorado',
        5 => 'Residência',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisai_aluno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'matricula', 'id_setor'], 'required'],
            [['matricula', 'id_setor', 'ativo', 'id_user', 'id_semestre', 'nivel_escolaridade'], 'integer'],
            [['nome'], 'string', 'max' => 60],
            [['email'], 'string', 'max' => 70],
            [['matricula'], 'unique', 'targetAttribute' => ['matricula']],
            [['id_setor'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhSetor::class, 'targetAttribute' => ['id_setor' => 'id_setor']],
            [['id_semestre'], 'exist', 'skipOnError' => true, 'targetClass' => SisccSemestre::class, 'targetAttribute' => ['id_semestre' => 'id_semestre']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'matricula' => 'Matrícula',
            'email' => 'E-mail',
            'id_setor' => 'Colegiado',
            'ativo' => 'Ativo',
            'id_semestre' => 'Semestre de Entrada',
            'nivel_escolaridade' => 'Nível de escolaridade do curso',
            'escolaridade' => 'Nível de escolaridade do curso',
            'id_user' => 'Usuário UFBA',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetor()
    {
        return $this->hasOne(SisrhSetor::class, ['id_setor' => 'id_setor']);
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
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisaiAvaliacaos()
    {
        return $this->hasMany(SisaiAvaliacao::class, ['id_aluno' => 'id_aluno']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitEnsOrientacaos()
    {
        return $this->hasMany(SispitEnsinoOrientacao::class, ['id_aluno' => 'id_aluno']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitMonitorias()
    {
        return $this->hasMany(SispitMonitoria::class, ['id_aluno' => 'id_aluno']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitOrientacaoAcademicas()
    {
        return $this->hasMany(SispitOrientacaoAcademica::class, ['id_aluno' => 'id_aluno']);
    }

    public function getMatriculaNome()
    {
        return "$this->matricula - $this->nome";
    }

    public function getColegiado()
    {
        if($this->id_setor == null)
            return null;
        
        return $this->setor->colegiado;
    }

    /**
     * @return string
     */
    public function getEscolaridade()
    {
        if($this->nivel_escolaridade === null)
            return '';
        return $this::ESCOLARIDADE[$this->nivel_escolaridade];
    }

    public function getColegiadoEscolaridade()
    {   
        return $this->colegiado.($this->escolaridade ? " ($this->escolaridade)": '');
    }

    /**
     * Retorna todos os alunos ativos
     */
    public static function getAtivos($select = '*', $order = 'nome')
    {
        return SisaiAluno::find()->select($select)->where(['ativo' => 1])->orderBy($order)->all();
    }

    public function afterFind()
    {
        parent::afterFind();
        $search = ['Da ','Das ','De ', 'Do ', 'Dos ', 'E '];
        $replace = ['da ','das ','de ','do ','dos ','e '];
        $this->nome = str_replace($search,$replace,mb_convert_case($this->nome, MB_CASE_TITLE, "UTF-8"));
    }

     /**
     * Verifica se o usuário está ativo e retorna objeto SisaiAluno referente a este usuário.
     * Função utilizada na tarefa de login
     */
    public static function usuarioAtivo($user)
    {
        $model = parent::find()->select('id_aluno,nome')->where(['id_user' => $user,'ativo' => 1])->one();

        return $model;
    }

    public static function atualizarTabelaSisaiAluno($arquivo)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try{
        
            $linhas = explode("\n", file_get_contents($arquivo));$contador = 0;
            $colegiado = $nivel = false;
            foreach($linhas as $linha){
                if(strlen(trim($linha)) == 0) //se tiver linha em branco, passa para a próxima
                    continue;
                if(substr($linha,0,1) == '<'){
                    $temp = explode('-',str_replace(['<','>'],['',''],$linha));//exclui os símbolos <>
                    $colegiado = $temp[0];
                    $nivel = isset($temp[1]) ? $temp[1] : 1;
                    SisaiAluno::updateAll(['ativo' => 0], ['id_setor'=>$colegiado, 'nivel_escolaridade' => $nivel]);
                }
                else{
                    $array = $nivel > 1 ? self::linhaPosgraduacao($linha) : self::linhaGraduacao($linha);
                    $aluno = self::find()->where(['matricula' => $array[1]])->one();
                    if($aluno == null)
                        $aluno = new SisaiAluno();
                    $aluno->nome = $array[0];
                    $aluno->matricula = $array[1];
                    $aluno->id_setor = $colegiado;
                    $aluno->nivel_escolaridade = $nivel;
                    $aluno->id_semestre = SisccSemestre::find()->select('id_semestre')->where(['ano' => $array[2][0], 'semestre' => $array[2][1]])->one()->id_semestre;
                    $aluno->ativo = 1;
                    $aluno->save();
                    $contador++;
                }
            }
            $transaction->commit();
        }catch(Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }

        return true;
    }

    protected static function linhaGraduacao($linha)
    {
        $temp = explode(' ', $linha);
        $contador = count($temp);
        $nome = $temp[2];
        for($i=3; $i < $contador - 1; $i++)
            $nome .= ' '.$temp[$i];
        $matricula = $temp[0];
        $semestre = $temp[$contador - 1];
        
        return [$nome,$matricula, explode('-',$semestre)];
    }

    protected static function linhaPosgraduacao($linha)
    {
        $temp = explode(' ', $linha);
        $contador = count($temp);
        $nome = $temp[0];
        for($i=1; $i < $contador - 2; $i++)
            $nome .= ' '.$temp[$i];
        $matricula = $temp[$contador - 2];
        $semestre = $temp[$contador - 1];
        
        return [$nome,$matricula, explode('.',$semestre)];
    }
}
