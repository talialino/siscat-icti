<?php

namespace app\modules\sisrh\models;

use Yii;
use \dektrium\user\models\User;
use app\modules\sispit\models\SispitPlanoRelatorio;

/**
 * This is the model class for table "sisrh_pessoa".
 *
 * @property int $id_pessoa
 * @property int $id_user
 * @property int $siape
 * @property string $nome
 * @property string $dt_nascimento
 * @property string $sexo
 * @property int $estado_civil
 * @property int $escolaridade
 * @property mixed $telefone
 * @property mixed $emails
 * @property int $regime_trabalho
 * @property string $dt_ingresso_orgao
 * @property int $id_cargo
 * @property int $id_classe_funcional
 * @property string $situacao
 * @property string $dt_exercicio
 * @property string $dt_vigencia
 * @property int $id_pessoa_vinculada
 * @property string $cpf
 *
 * @property SisrhAfastamento[] $sisrhAfastamentos
 * @property SisrhCargo $cargo
 * @property SisrhClasseFuncional $classeFuncional
 * @property User $user
 * @property SisrhSetorPessoa[] $sisrhSetorPessoas
 */
class SisrhPessoa extends \yii\db\ActiveRecord
{
    public const ESTADOCIVIL = [
        1 => 'Solteiro',
        2 => 'Casado',
        3 => 'Separado Judicialmente',
        4 => 'Separado Consensualmente',
        5 => 'Viuvo',
        6 => 'Desquitado',
        7 => 'Divorciado',
        8 => 'Outros',
    ];
    public const ESCOLARIDADE = [
        1 => 'Analfabeto',
        2 => 'Ensino Fundamental Incompleto',
        3 => 'Ensino Fundamental',
        4 => 'Ensino Médio',
        5 => 'Ensino Superior',
        6 => 'Aperfeiçoamento',
        7 => 'Especialização',
        8 => 'Mestrado',
        9 => 'Doutorado',
        10 => 'Pós Doutorado',
        11 => 'Não Informado'
    ];
    public const JORNADA = [
        20 => '20 horas semanais',
        25 => '25 horas semanais',
        30 => '30 horas semanais',
        40 => '40 horas semanais',
        99 => 'Dedicação exclusiva'
    ];
    // public const NACIONALIDADE = [
    //     0 => 'Estrangeira',
    //     1 => 'Brasileira'
    // ];

    /**
     * O atributo a seguir é utilizado pelo método getSispitPlanoRelatorio para retornar
     * o pit/rit referente ao ano deste atributo.
     * @property int
     */
    public $sispitAno;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sisrh_pessoa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'siape', 'estado_civil', 'escolaridade', 'regime_trabalho', 'id_cargo', 'id_classe_funcional','situacao', 'id_pessoa_vinculada'], 'integer'],
            [['nome'], 'required'],
            [['dt_nascimento', 'dt_exercicio', 'dt_ingresso_orgao', 'dt_vigencia'], 'safe'],
            [['nome'], 'string', 'max' => 100],
            [['sexo'], 'string', 'max' => 1],
            [['cpf'], 'validateCPF'],
            [['siape'], 'unique', 'targetAttribute' => ['siape']],
            [['emails', 'telefone'], 'string', 'max' => 255],
            [['id_cargo'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhCargo::class, 'targetAttribute' => ['id_cargo' => 'id_cargo']],
            [['id_classe_funcional'], 'exist', 'skipOnError' => true, 'targetClass' => SisrhClasseFuncional::class, 'targetAttribute' => ['id_classe_funcional' => 'id_classe_funcional']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pessoa' => 'Id Pessoa',
            'id_user' => 'Usuário UFBA',
            'siape' => 'SIAPE',
            'nome' => Yii::t('app','Name'),
            'dt_nascimento' => Yii::t('app','Birthday'),
            'sexo' => Yii::t('app','Sex'),
            'estado_civil' => Yii::t('app','Civil Status'),
            'estadoCivil' => Yii::t('app','Civil Status'),
            'escolaridade' => Yii::t('app','Schooling'),
            'grauEscolaridade' => Yii::t('app','Schooling'),
            'telefone' => Yii::t('app','Telephone'),
            'emails' => Yii::t('app','Others Emails'),
            'regime_trabalho' => Yii::t('app','Work Regime'),
            'jornada' => Yii::t('app','Work Regime'),
            'dt_ingresso_orgao' => Yii::t('app','Date of Entry into the Unit'),
            'dt_exercicio' => 'Data de Efetivo Exercício',
            'dt_vigencia' => 'Data de Vigência',
            'id_cargo' => Yii::t('app','Office'),
            'id_classe_funcional' => Yii::t('app','Functional Class'),
            'situacao' => Yii::t('app','Status'),
            'id_pessoa_vinculada' => 'Docente Vinculado(a)',
            'nomePessoaVinculada' => 'Docente Vinculado(a)',
            'cpf' => 'CPF',
        ];
    }

    public function getEstadoCivil()
    {
        if($this->estado_civil === null)
            return null;
        return $this::ESTADOCIVIL[$this->estado_civil];
    }

    public function getGrauEscolaridade()
    {
        if($this->escolaridade === null)
            return null;
        return $this::ESCOLARIDADE[$this->escolaridade];
    }

    public function getJornada()
    {
        if($this->regime_trabalho === null)
            return null;
        return $this::JORNADA[$this->regime_trabalho];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAfastamentos()
    {
        return $this->hasMany(SisrhAfastamento::class, ['id_pessoa' => 'id_pessoa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCargo()
    {
        return $this->hasOne(SisrhCargo::class, ['id_cargo' => 'id_cargo']);
    }

    public function getCategoria()
    {
        return $this->hasOne(SisrhCategoria::class, ['id_categoria' => 'id_categoria'])->via('cargo');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClasseFuncional()
    {
        return $this->hasOne(SisrhClasseFuncional::class, ['id_classe_funcional' => 'id_classe_funcional']);
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
    public function getPessoaVinculada()
    {
        return $this->hasOne(self::class, ['id_pessoa' => 'id_pessoa_vinculada']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getnomePessoaVinculada()
    {
        if($this->id_pessoa_vinculada == null)
            return null;
        return $this->pessoaVinculada->nome;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSispitPlanoRelatorio()
    {
        if($this->sispitAno == null)
            return null;
        return SispitPlanoRelatorio::find()->where(['user_id' => $this->id_user,
            'id_ano' => $this->sispitAno]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhSetorPessoas()
    {
        return $this->hasMany(SisrhSetorPessoa::class, ['id_pessoa' => 'id_pessoa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhSetores()
    {
        return $this->hasMany(SisrhSetor::class, ['id_setor' => 'id_setor'])->via('sisrhSetorPessoas');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhComissaoPessoas()
    {
        return $this->hasMany(SisrhComissaoPessoa::class, ['id_pessoa' => 'id_pessoa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSisrhComissoes()
    {
        return $this->hasMany(SisrhComissao::class, ['id_comissao' => 'id_comissao'])->via('sisrhComissaoPessoas');
    }

    /**
     * Método que capitaliza o nome da pessoa, excetuando as preposições;
     * e transforma emails e telefone em array, visto que podem representar mais de um
     * valor
     */
    public function afterFind()
    {
        parent::afterFind();
        $search = ['Da ','Das ','De ', 'Do ', 'Dos ', 'E '];
        $replace = ['da ','das ','de ','do ','dos ','e '];
        $this->nome = str_replace($search,$replace,mb_convert_case($this->nome, MB_CASE_TITLE, "UTF-8"));
        $this->emails = explode(',',$this->emails);
        //Se o primeiro valor de e-mail não tiver o símbolo @, significa que o campo está vazio
        if(!filter_var($this->emails[0], FILTER_VALIDATE_EMAIL))
            $this->emails = array();
        $this->telefone = explode(',',$this->telefone);
    }

    /**
     * Retorna todos os servidores ativos
     */
    public static function getAtivos($select = '*', $order = 'nome')
    {
        return SisrhPessoa::find()->select($select)->where(['situacao' => 1])->orderBy($order)->all();
    }

    /**
     * Verifica se o usuário está ativo e retorna objeto Pessoa referente a este usuário.
     * Função utilizada na tarefa de login
     */
    public static function usuarioAtivo($user)
    {
        $model = parent::find()->select('id_pessoa,nome')->where(['id_user' => $user,'situacao' => 1])->one();

        //$diaAtual = date('Y-m-d');
        //$afastamento = SisrhAfastamento::find()->where(['=','id_pessoa', $model->id_pessoa])->andWhere(['<=','inicio',$diaAtual])->andWhere(['>=','termino',$diaAtual])->one();

        return /*isset($afastamento) ? null : */$model;
    }

    public function beforeValidate(){
        if(is_array($this->emails)){
            $contador = 0;
            foreach($this->emails as $email){
                $contador++;
                if($contador == 0)
                    $this->addError('emails','O primeiro campo de e-mail não pode ficar vazio.');
                if(filter_var($email, FILTER_VALIDATE_EMAIL))
                    continue;
                if($contador == 1 && strlen(trim($email)) == 0)
                    $contador = -1;
                else
                    $this->addError('emails','Formato de e-mail não válido!');
            }
            if(!$this->hasErrors('emails'))
                $this->emails = implode(',',$this->emails);
        }
        if(is_array($this->telefone))
            $this->telefone = implode(',',$this->telefone);
        return parent::beforeValidate();
    }

    public function temOcorrenciaVigente()
    {
        $ocorrencias = $this->afastamentos;
        $ocorrenciaVigente = false;

        foreach($ocorrencias as $ocorrencia){
            
            if(strtotime($ocorrencia->inicio) <= strtotime('today'))
            {
                if(!$ocorrencia->termino || strtotime($ocorrencia->termino) >= strtotime('today'))
                    $ocorrenciaVigente = true;
            }
        }

        return $ocorrenciaVigente;
    }

    public function validateCPF()
    {

         // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $this->cpf );
        
        
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            $this->addError('cpf','CPF inválido');
            return;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $this->addError('cpf','CPF inválido');
            return;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $this->addError('cpf','CPF inválido');
                return;
            }
        }
    }
}
