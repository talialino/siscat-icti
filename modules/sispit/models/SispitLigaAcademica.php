<?php

namespace app\modules\sispit\models;

use app\modules\sisliga\models\SisligaLigaAcademica;
use Yii;

use app\modules\sispit\config\LimiteCargaHoraria;

/**
 * This is the model class for table "sispit_liga_academica".
 *
 * @property int $id_sispit_liga_academica
 * @property int $id_plano_relatorio
 * @property int $id_liga_academica
 * @property int $semestre
 * @property int $funcao
 * @property int $carga_horaria
 * @property int $pit_rit
 *
 * @property SisligaLigaAcademica $ligaAcademica
 * @property SispitPlanoRelatorio $planoRelatorio
 */
class SispitLigaAcademica extends \yii\db\ActiveRecord
{
    public const FUNCAO = [1 => 'Tutor', 2 => 'Vice-Tutor'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sispit_liga_academica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plano_relatorio', 'id_liga_academica','semestre'], 'required'],
            [['id_plano_relatorio', 'id_liga_academica', 'semestre', 'funcao', 'carga_horaria', 'pit_rit'], 'integer'],
            [['carga_horaria'], 'validateCargaHoraria'],
            [['id_liga_academica'], 'exist', 'skipOnError' => true, 'targetClass' => SisligaLigaAcademica::class, 'targetAttribute' => ['id_liga_academica' => 'id_liga_academica']],
            [['id_plano_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => SispitPlanoRelatorio::class, 'targetAttribute' => ['id_plano_relatorio' => 'id_plano_relatorio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_liga_academica' => 'Liga Acadêmica',
            'semestre' => 'Semestre',
            'funcao' => 'Função',
            'funcaoString' => 'Função',
            'carga_horaria' => 'Carga Horária',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLigaAcademica()
    {
        return $this->hasOne(SisligaLigaAcademica::class, ['id_liga_academica' => 'id_liga_academica']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoRelatorio()
    {
        return $this->hasOne(SispitPlanoRelatorio::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    public function getFuncaoString()
    {
        if($this->funcao === null)
            return null;
        return $this::FUNCAO[$this->funcao];
    }

    public function validateCargaHoraria()
    {
        if($this->funcao == null)
        {
            $this->addError('carga_horaria', 'Selecione primeiro a função para depois inserir a carga horária.');
            return;
        }
        switch($this->funcao)
        {
            case 1:
                if($this->carga_horaria > 2)
                    $this->addError('carga_horaria', 'A carga horária não pode ser superior a 2.');
            break;
            case 2:
                if($this->carga_horaria > 1)
                    $this->addError('carga_horaria', 'A carga horária não pode ser superior a 1.');
            break;
        }
        $total = 0;
        $excluirAtual = $this->isNewRecord ? '' : 
            "AND id_sispit_liga_academica <> $this->id_sispit_liga_academica";
        
        if($this->id_plano_relatorio && $this->semestre)
        {
            $total = Yii::$app->db->createCommand("SELECT COALESCE(SUM(carga_horaria),0) AS total
                FROM sispit_pesquisa_extensao WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $this->pit_rit AND semestre = $this->semestre")
                ->queryAll()[0]['total'];

            $total += Yii::$app->db->createCommand("SELECT COALESCE(SUM(carga_horaria),0) AS total
                FROM sispit_liga_academica WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $this->pit_rit AND semestre = $this->semestre $excluirAtual")
                ->queryAll()[0]['total'];
        }
        
        if(($this->carga_horaria + $total) > LimiteCargaHoraria::LIMITES['pesquisa_extensao'])
            $this->addError('carga_horaria',
                'As cargas horárias de pesquisa e extensão somadas com as cargas horárias de ligas acadêmicas não podem ser superiores a 20.');
   }

   /**
    * Método que lista todas as Ligas vinculadas
    */
   public function listaLigas()
    {
        $plano = $this->planoRelatorio;
        $id_pessoa = $plano->pessoa->id_pessoa;
        $query = SisligaLigaAcademica::find()->select(['sisliga_liga_academica.id_liga_academica', 'nome'])->distinct();
        $query->joinWith('sisligaLigaIntegrantes as integrante')->where([
            'sisliga_liga_academica.id_pessoa' => $id_pessoa])->orWhere([
            'integrante.id_pessoa' => $id_pessoa]);
        if($plano->isRitAvailable())//no caso do rit, só podem ser Ligas homologadas pela congregação
            $query->andWhere(['situacao' => [7,9,10]]);
        return $query->orderby('nome')->all();
    }
}