<?php

namespace app\modules\sisai\models;

use app\modules\sisrh\models\SisrhSetor;
use Exception;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\Query;

class AdicionarComponentesForm extends Model
{
    public $componentes;

    public function attributeLabels()
    {
        return [
            'componentes' => 'Selecione os Componentes Curriculares com seus respectivos Docentes',
        ];
    }

    public function rules()
    {
        return [
            [['componentes'], 'required'],
        ];
    }

    /**
     * o método a seguir retorna os componentes curriculares, com seus respectivos colegiados e docentes
     * para o semestre do período de avaliação vigente.
     * @return array
     */
    public static function listaComponentes()
    {
        $saida = array();
        $periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao');
        if($periodoAvaliacao)
        {
            $id_semestre = $periodoAvaliacao->id_semestre;
            $query = new Query;
            $query->select('pc.id_setor, s.nome AS colegiado, pc.id_componente_curricular, c.codigo_componente, c.nome AS componente, pp.id_pessoa, p.nome AS docente')
                ->from('siscc_programa_componente_curricular pc, siscc_componente_curricular c,
                    sisrh_pessoa p, siscc_programa_componente_curricular_pessoa pp, sisrh_setor s')
                ->where("pc.id_semestre = $id_semestre AND pc.id_setor = s.id_setor AND pc.id_componente_curricular = c.id_componente_curricular
                    AND pc.id_programa_componente_curricular = pp.id_programa_componente_curricular AND pp.id_pessoa = p.id_pessoa")
                ->orderBy('colegiado, componente, docente');
            $result = $query->all();
            
            foreach($result as $linha)
            {
                $colegiado = SisrhSetor::ajustarNomeColegiado($linha['colegiado']);
                $saida[$colegiado]["{$linha['id_setor']}-{$linha['id_componente_curricular']}-{$linha['id_pessoa']}"] = 
                    "{$linha['codigo_componente']} - {$linha['componente']} - {$linha['docente']} ($colegiado)";
            }
            
        }
        return $saida;
    }

    /**
     * Salva os componentes curriculares no BD
     */
    public function save($avaliacao)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            
            foreach($this->componentes as $componente){
                
                $array = explode('-', $componente);
                
                //Caso o aluno esteja adicionando um componente depois de já ter feito outras avaliações
                //o sistema verifica se o componente que ele está querendo adicionar não foi avaliado por ele.
                if($avaliacao->situacao > 1 && SisaiAlunoComponenteCurricular::find()->where([
                    'id_avaliacao' => $avaliacao->id_avaliacao, 'id_setor' => $array[0],
                    'id_componente_curricular' => $array[1], 'id_pessoa' => $array[2],
                ])->exists())
                    continue;
                
                $alunocc = new SisaiAlunoComponenteCurricular();
                $alunocc->id_avaliacao = $avaliacao->id_avaliacao;
                $alunocc->id_setor = $array[0];
                $alunocc->id_componente_curricular = $array[1];
                $alunocc->id_pessoa = $array[2];
                
                if(!$alunocc->save())
                    throw new Exception('Não foi possível salvar no banco de dados');
            }
            
            $avaliacao->situacao = $avaliacao->situacao == 0 ? 1 : 98;
            
            if(!$avaliacao->save())
                throw new Exception('Não foi possível salvar no banco de dados');

            $transaction->commit();
            return true;
        }
        catch(Throwable $e)
        {
            $transaction->rollBack();
            return false;
        }
    }
}