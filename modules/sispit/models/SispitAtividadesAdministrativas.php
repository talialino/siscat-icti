<?php

namespace app\modules\sispit\models;

use Yii;
use app\modules\sispit\config\LimiteCargaHoraria;

/**
 * This is the model class for table "sispit_atividades_administrativas".
 *
 * @property int $id_atividades_administrativas
 * @property int $id_plano_relatorio
 * @property int $tipo_atividade
 * @property int $semestre
 * @property int $tipo_membro
 * @property string $descricao
 * @property int $carga_horaria
 * @property int $pit_rit
 *
 * @property SispitPlanoRelatorio $planoRelatorio
 */
class SispitAtividadesAdministrativas extends \yii\db\ActiveRecord
{
    /*A seguir está uma lista das atividades administrativas possíveis
      Por conta de adições solicitadas depois do sistema ter sido lançado, e para manter uma ordem mais amigável para o usuário
      os valores não estão totalmente sequenciais, por isso, adicionei aqui no comentário o maior valor já atribuído para facilitar
      inclusão de novos valores sem ocasionar conflito.
      Maior valor: 55

      Lembrando que ao adicionar um valor na tabela abaixo, também é necessário adicionar os valores referentes ao limite de carga
      horária alterando o valor da constante app\modules\sispit\config\LimiteCargaHoraria::LIMITES['atividades_administrativas']
    */
    public const TIPO_ATIVIDADE = [
        'Direção' => [ 1 => 'Diretor(a)', 2 => 'Vice-diretor(a)'],
        'Congregação' => [ 3 => 'Membro Titular'],
        'Coordenação Acadêmica de Ensino' => [ 5 => 'Coordenador(a)', 55 => 'Vice-coordenador(a)', 6 => 'Membro Titular'],
        'Colegiado' => [8 => 'Coordenador(a)', 9 => 'Vice-coordenador(a)', 10 => 'Membro Titular'],
        'Núcleo Acadêmico' => [12 => 'Coordenador(a)', 13 => 'Vice-coordenador(a)', 14 => 'Membro'],
        'Coordenação Acadêmica de Pesquisa e Extensão' => [15 => 'Coordenador(a)', 41 => 'Vice-coordenador(a)', 42 => 'Membro Titular'],
        'Coordenação Geral de Laboratórios' => [16 => 'Coordenador(a)'],
        'Representação em órgãos superiores da UFBA (CAE, Consepe, Capex, CPPD)' => [18 => 'Membro Titular'],
        'Cargo de Assessoria' => [20 => 'Assessor(a)'],
        'Comitê de Ética em Pesquisa em Seres Humanos - CEP' => [21 => 'Coordenador(a)', 22 => 'Vice-coordenador(a)', 23 => 'Membro'],
        'Comissão de Ética no Uso de Animais - CEUA' => [24 => 'Coordenador(a)', 25 => 'Vice-coordenador(a)', 26 => 'Membro'],
        'Comissão de Avaliação Institucional - CAVI' => [27 => 'Coordenador(a)', 28 => 'Vice-coordenador(a)', 29 => 'Membro'],
        'Comissão Interna de Biossegurança - CIBIO' => [30 => 'Coordenador(a)', 31 => 'Vice-coordenador(a)', 32 => 'Membro'],
        'Comissão de Formação Docente' =>  [45 => 'Coordenador(a)', 46 => 'Vice-coordenador(a)', 47 => 'Membro'],
        'Comissão de Avaliação de PIT e RIT Docente' => [48 => 'Presidente', 50 => 'Membro'],
        'Comissão de Progressão Docente' => [51 => 'Presidente', 52 => 'Membro'],
        'Comissão de Estágio Probatório Docente' => [53 => 'Presidente', 54 => 'Membro'],
        'Coordenação de Ações Afirmativas e Assistência Estudantil - COAE' => [33 => 'Coordenador(a)'],
        'Outras Comissões, Comitês ou Grupos de Trabalho' => [34 => 'Presidente', 35 => 'Membro Titular'],
        'Núcleo de Docente Estruturante' => [37 => 'Presidente', 38 => 'Membro Titular'],
        'Biotério' => [40 => 'Responsável'],
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_atividades_administrativas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio','tipo_atividade', 'semestre'], 'required'],
            [['id_plano_relatorio', 'tipo_atividade', 'semestre', 'carga_horaria', 'pit_rit'], 'integer'],
            [['carga_horaria'],'validateCargaHoraria'],
            [['descricao'], 'string', 'max' => 100],
            [['id_plano_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SispitPlanoRelatorio::class, 'targetAttribute' => ['id_plano_relatorio' => 'id_plano_relatorio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tipo_atividade' => 'Tipo de Atividade',
            'tipoAtividade' => 'Tipo de Atividade',
            'semestre' => 'Semestre',
            'descricao' => 'Descrição',
            'carga_horaria' => 'Carga Horária',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    public function getTipoAtividade()
    {
        $tiposExcluidos = [
            'Congregação' => [4 => 'Membro Suplente'],
            'Coordenação Acadêmica de Ensino' => [7 => 'Membro Suplente'],
            'Colegiado' => [11 => 'Membro Suplente'],
            'Coordenação Acadêmica de Pesquisa e Extensão' => [43 => 'Membro Suplente'],
            'Coordenação Geral de Laboratórios' => [17 => 'Vice-coordenador(a)'],
            'Membros de Órgãos Superiores da UFBA' => [19 => 'Membro Suplente'],
            'Comissão de Avaliação de PIT e RIT Docente' => [49 => 'Vice-coordenador(a)'],
            'Outras Comissões, Comitês ou Grupos de Trabalho' => [36 => 'Membro Suplente'],
            'Núcleo de Docente Estruturante' => [39 => 'Membro Suplente'],
            'Outras' => [44 => 'Outras atividades'],
        ];
        foreach ($this::TIPO_ATIVIDADE as $setor => $tipos)
            foreach ($tipos as $k => $v)
                if($this->tipo_atividade == $k)
                    return "$v - $setor";

        foreach ($tiposExcluidos as $setor => $tipos)
            foreach ($tipos as $k => $v)
                if($this->tipo_atividade == $k)
                    return "$v - $setor";

        return "Valor não encontrado";
    }

    public function validateCargaHoraria(){
        if($this->tipo_atividade !== null){
            $limiteCH = LimiteCargaHoraria::LIMITES['atividades_administrativas'][$this->tipo_atividade];
            if($this->carga_horaria > $limiteCH)
            {
                $this->addError('carga_horaria', "Carga Horária não pode ser maior que $limiteCH.");
            }
        }
    }
}
