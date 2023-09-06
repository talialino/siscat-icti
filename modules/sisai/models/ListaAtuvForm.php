<?php

namespace app\modules\sisai\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\Query;

class ListaAtuvForm extends Model
{
    public $id_semestre;

    public function attributeLabels()
    {
        return [
            'id_semestre' => 'Semestre atual (semestre matriculado)',
        ];
    }

    public function rules()
    {
        return [
            [['id_semestre'], 'required'],
            [['id_semestre'], 'integer'],
        ];
    }

    public function search()
    {
        //$query = SisaiAluno::find()->select(['nome','matricula','id_setor','id_semestre','nivel_escolaridade']);

        $query = new Query;
        $colegiados = SisaiColegiadoSemestreAtuv::find()->where(['id_semestre' => $this->id_semestre])->one();
        if(!$colegiados)
            return [];
        $query->select('a.nome, a.matricula, a.nivel_escolaridade, s.nome AS colegiado, sa.ano AS anoAtual, sa.semestre AS semestreAtual,
                se.ano AS anoEntrada, se.semestre AS semestreEntrada')
            ->from('sisai_aluno a, sisrh_setor s, siscc_semestre sa, siscc_semestre se')
            ->where("a.id_setor=s.id_setor AND a.id_semestre = se.id_semestre AND sa.id_semestre = $this->id_semestre")
            ->andWhere(['a.id_setor' => $colegiados->colegiados_liberados])
            ->orderBy('a.nivel_escolaridade, colegiado,a.nome');
        return $query->all();
        
        // $provider = new ArrayDataProvider([
        //     'allModels' => $query->all(),
        //     'pagination' => false,
        // ]);

        // return $provider;
    }

    /**
     * o método a seguir serve para retornar o turno de cada curso. No momento da criação deste método
     * todos os cursos são diurnos. Mas caso haja alterações no futuro, essas devem ser especificadas aqui
     * @param integer $colegiado
     * @param integer $nivel_escolaridade
     * @return string
     */
    public static function turno($colegiado = 0, $nivel_escolaridade = 0)
    {
        return 'Diurno';
    }

    /**
     * Função que calcula o número provavel de semestres cursados
     */
    public static function semestresCursados($anoEntrada,$semestreEntrada,$anoAtual,$semestreAtual)
    {
        $resultado = 2 * ($anoAtual - $anoEntrada);
        if($anoEntrada <= 2020)
            $resultado--;
        switch($semestreAtual-$semestreEntrada)
        {
            case -1:
                $resultado--;
            break;
            case 1:
                $resultado++;
        }
        return $resultado < 0 ? 0 : $resultado;
    }
}