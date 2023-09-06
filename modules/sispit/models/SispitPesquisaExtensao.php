<?php

namespace app\modules\sispit\models;

use Yii;

use app\modules\sisape\models\SisapeProjeto;
use app\modules\sispit\config\LimiteCargaHoraria;

/**
 * This is the model class for table "sispit_pesquisa_extensao".
 *
 * @property int $id_pesquisa_extensao
 * @property int $id_plano_relatorio
 * @property int $id_projeto
 * @property int $semestre
 * @property int $tipo_participacao
 * @property int $tipo_extensao
 * @property int $carga_horaria
 * @property int $pit_rit
 *
 * @property SisapeProjeto $projeto
 * @property SispitPlanoRelatorio $planoRelatorio
 */
class SispitPesquisaExtensao extends \yii\db\ActiveRecord
{
    public const TIPO_PARTICIPACAO = ['Coordenação de Projeto (100% da CH)','Participação/Colaboração (50% da CH)'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_pesquisa_extensao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio', 'id_projeto','semestre'], 'required'],
            [['id_plano_relatorio', 'id_projeto', 'semestre', 'tipo_participacao', 'carga_horaria', 'pit_rit'], 'integer'],
            [['carga_horaria'], 'validateCargaHoraria'],
            [['id_projeto'], 'exist', 'skipOnError' => true, 'targetClass' => SisapeProjeto::class, 'targetAttribute' => ['id_projeto' => 'id_projeto']],
            [['id_plano_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SispitPlanoRelatorio::class, 'targetAttribute' => ['id_plano_relatorio' => 'id_plano_relatorio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_projeto' => 'Projeto',
            'semestre' => 'Semestre',
            'tipo_participacao' => 'Tipo de Participação',
            'tipoParticipacao' => 'Tipo de Participação',
            'tipo' => 'Tipo',
            'carga_horaria' => 'Carga Horária',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto()
    {
        return $this->hasOne(SisapeProjeto::class, ['id_projeto' => 'id_projeto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    public function getTipo()
    {
        if($this->id_projeto === null)
            return null;
        return $this->projeto->tipoProjeto;
    }

    public function getTipoParticipacao()
    {
        if($this->tipo_participacao === null)
            return null;
        return $this::TIPO_PARTICIPACAO[$this->tipo_participacao];
    }

    public function validateCargaHoraria()
    {
        $total = 0;
        $excluirAtual = $this->isNewRecord ? '' : 
            "AND id_pesquisa_extensao <> $this->id_pesquisa_extensao";
        
        if($this->id_plano_relatorio && $this->semestre)
        {
            $total = Yii::$app->db->createCommand("SELECT COALESCE(SUM(carga_horaria),0) AS total
                FROM sispit_pesquisa_extensao WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $this->pit_rit AND semestre = $this->semestre $excluirAtual")
                ->queryAll()[0]['total'];

            $total += Yii::$app->db->createCommand("SELECT COALESCE(SUM(carga_horaria),0) AS total
                FROM sispit_liga_academica WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $this->pit_rit AND semestre = $this->semestre")
                ->queryAll()[0]['total'];
        }
        
        if(($this->carga_horaria + $total) > LimiteCargaHoraria::LIMITES['pesquisa_extensao'])
            $this->addError('carga_horaria',
                'As cargas horárias de pesquisa e extensão somadas com as cargas horárias de ligas acadêmicas não podem ser superiores a 20.');
   }

   /**
    * Método que lista todos os projetos vinculados
    */
   public function listaProjetos()
    {
        $plano = $this->planoRelatorio;
        $id_pessoa = $plano->pessoa->id_pessoa;
        $query = SisapeProjeto::find()->select(['sisape_projeto.id_projeto', 'titulo'])->distinct();
        $query->joinWith('sisapeProjetoIntegrantes as integrante')->where([
            'sisape_projeto.id_pessoa' => $id_pessoa])->orWhere([
            'integrante.id_pessoa' => $id_pessoa]);
        if($plano->isRitAvailable())//no caso do rit, só podem ser projetos homologados pela congregação
            $query->andWhere(['situacao' => [12,14]]);
        return $query->orderby('titulo')->all();
    }
}