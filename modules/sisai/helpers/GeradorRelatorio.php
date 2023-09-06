<?php

namespace app\modules\sisai\helpers;

use app\modules\sisai\models\RelatorioForm;
use app\modules\sisai\models\RelatorioQuantitativo;
use app\modules\sisai\models\SisaiPeriodoAvaliacao;
use app\modules\sisai\models\SisaiQuestionario;
use yii\data\ArrayDataProvider;
use yii\db\Query;

class GeradorRelatorio
{
    public static function relatorioAvaliacaoDocente(RelatorioForm $relatorioForm)
    {
        $periodoAvaliacao = SisaiPeriodoAvaliacao::findOne($relatorioForm->id_semestre);
        $cc = explode('-',$relatorioForm->componente_colegiado);
        $questionario = SisaiQuestionario::findOne($periodoAvaliacao->getIdQuestionario(2,[
            'componente' => $cc[0],
            'colegiado' => $cc[1],
            'docente' => $relatorioForm->id_pessoa,
        ]));
        $perguntas = $questionario->sisaiPerguntas;
        
        $saida = [];
        foreach($perguntas as $pergunta){
            if($pergunta->tipo_pergunta != $pergunta::PADRAO)
                continue;
            $query = new Query();
            $resultado = $query->select('resposta,count(resposta) as total')
                ->from('sisai_aluno_resposta_objetiva r, sisai_avaliacao a, sisai_aluno_componente_curricular c')
                ->where('r.id_avaliacao = a.id_avaliacao AND a.id_semestre = :semestre AND r.id_pergunta = :pergunta
                    AND r.id_aluno_componente_curricular = c.id_aluno_componente_curricular
                    AND c.id_componente_curricular = :componente AND c.id_pessoa = :pessoa AND c.id_setor = :setor',[
                    ':semestre' => $relatorioForm->id_semestre,':componente' => $cc[0],':pessoa' => $relatorioForm->id_pessoa,
                    ':setor' => $cc[1], ':pergunta' => $pergunta->id_pergunta])
                ->groupBy('resposta')->all();
            if(count($resultado) == 0)
                break;
            $respostas = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            foreach($resultado as $linha)
                $respostas[$linha['resposta']] = $linha['total'];
            $saida[] = new RelatorioQuantitativo($pergunta->descricao, $respostas[5],$respostas[4],$respostas[3],$respostas[2],$respostas[1]);
        }
      
        return [new ArrayDataProvider([
            'allModels' => $saida,
            'pagination' => false,
        ]), $questionario->tipo_questionario];
    }

    public static function relatorioAvaliacaoDiscente(RelatorioForm $relatorioForm)
    {
        $periodoAvaliacao = SisaiPeriodoAvaliacao::findOne($relatorioForm->id_semestre);
        $questionario = SisaiQuestionario::findOne($periodoAvaliacao->getIdQuestionario(4));
        $perguntas = $questionario->sisaiPerguntas;
        
        $saida = [];
        foreach($perguntas as $pergunta){
            if($pergunta->tipo_pergunta != $pergunta::PADRAO)
                continue;
            $query = new Query();
            $resultado = $query->select('resposta,count(resposta) as total')
                ->from('sisai_professor_resposta_objetiva r, sisai_avaliacao a, sisai_professor_componente_curricular c')
                ->where('r.id_avaliacao = a.id_avaliacao AND a.id_semestre = :semestre AND r.id_pergunta = :pergunta
                    AND r.id_professor_componente_curricular = c.id_professor_componente_curricular
                    AND c.id_setor = :setor',[
                    ':semestre' => $relatorioForm->id_semestre, ':setor' => $relatorioForm->id_setor, ':pergunta' => $pergunta->id_pergunta])
                ->groupBy('resposta')->all();
            if(count($resultado) == 0)
                break;
            $respostas = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            foreach($resultado as $linha)
                $respostas[$linha['resposta']] = $linha['total'];
            $saida[] = new RelatorioQuantitativo($pergunta->descricao, $respostas[5],$respostas[4],$respostas[3],$respostas[2],$respostas[1]);
        }
      
        return new ArrayDataProvider([
            'allModels' => $saida,
            'pagination' => false,
        ]);
    }

    public static function relatorioAutoavaliacaoDiscente(RelatorioForm $relatorioForm)
    {
        $periodoAvaliacao = SisaiPeriodoAvaliacao::findOne($relatorioForm->id_semestre);
        $questionario = SisaiQuestionario::findOne($periodoAvaliacao->getIdQuestionario(1));
        $perguntas = $questionario->sisaiPerguntas;
        
        $saida = [];
        foreach($perguntas as $pergunta){
            if($pergunta->tipo_pergunta != $pergunta::PADRAO)
                continue;
            $query = new Query();
            $resultado = $query->select('resposta,count(resposta) as total')
                ->from('sisai_aluno_resposta_objetiva r, sisai_avaliacao a, sisai_aluno d')
                ->where('r.id_avaliacao = a.id_avaliacao AND a.id_semestre = :semestre AND r.id_pergunta = :pergunta
                    AND a.id_aluno = d.id_aluno AND d.id_setor = :setor',[
                    ':semestre' => $relatorioForm->id_semestre, ':setor' => $relatorioForm->id_setor, ':pergunta' => $pergunta->id_pergunta])
                ->groupBy('resposta')->all();
            if(count($resultado) == 0)
                break;
            $respostas = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            foreach($resultado as $linha)
                $respostas[$linha['resposta']] = $linha['total'];
            $saida[] = new RelatorioQuantitativo($pergunta->descricao, $respostas[5],$respostas[4],$respostas[3],$respostas[2],$respostas[1]);
        }
      
        return new ArrayDataProvider([
            'allModels' => $saida,
            'pagination' => false,
        ]);
    }

    public static function relatorioAutoavaliacaoDocente(RelatorioForm $relatorioForm)
    {
        $periodoAvaliacao = SisaiPeriodoAvaliacao::findOne($relatorioForm->id_semestre);
        $questionario = SisaiQuestionario::findOne($periodoAvaliacao->getIdQuestionario(3));
        $perguntas = $questionario->sisaiPerguntas;
        
        $saida = [];
        foreach($perguntas as $pergunta){
            if($pergunta->tipo_pergunta != $pergunta::PADRAO)
                continue;
            $query = new Query();
            $resultado = $query->select('resposta,count(resposta) as total')
                ->from('sisai_professor_resposta_objetiva r, sisai_avaliacao a, sisrh_setor_pessoa s')
                ->where('r.id_avaliacao = a.id_avaliacao AND a.id_semestre = :semestre AND r.id_pergunta = :pergunta
                    AND a.id_pessoa = s.id_pessoa AND s.id_setor = :setor',[
                    ':semestre' => $relatorioForm->id_semestre, ':setor' => $relatorioForm->id_setor, ':pergunta' => $pergunta->id_pergunta])
                ->groupBy('resposta')->all();
            if(count($resultado) == 0)
                break;
            $respostas = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            foreach($resultado as $linha)
                $respostas[$linha['resposta']] = $linha['total'];
            $saida[] = new RelatorioQuantitativo($pergunta->descricao, $respostas[5],$respostas[4],$respostas[3],$respostas[2],$respostas[1]);
        }
      
        return new ArrayDataProvider([
            'allModels' => $saida,
            'pagination' => false,
        ]);
    }

    public static function relatorioAvaliacaoAmbienteVirtual(RelatorioForm $relatorioForm)
    {
        $periodoAvaliacao = SisaiPeriodoAvaliacao::findOne($relatorioForm->id_semestre);
        $questionarioDiscente = SisaiQuestionario::findOne($periodoAvaliacao->getIdQuestionario(7));
        $questionarioDocente = SisaiQuestionario::findOne($periodoAvaliacao->getIdQuestionario(8));
        $perguntasDiscente = $questionarioDiscente->sisaiPerguntas;
        $perguntasDocente = $questionarioDocente->sisaiPerguntas;
        
        $saida = [];
        foreach($perguntasDocente as $pergunta){
            if($pergunta->tipo_pergunta == $pergunta::ABERTA)
                continue;
            $query = new Query();
            $resultado = $query->select('resposta,count(resposta) as total')
                ->from('sisai_professor_resposta_objetiva r, sisai_avaliacao a')
                ->where('r.id_avaliacao = a.id_avaliacao AND a.id_semestre = :semestre AND r.id_pergunta = :pergunta',
                [':semestre' => $relatorioForm->id_semestre, ':pergunta' => $pergunta->id_pergunta])
                ->groupBy('resposta')->all();
            if(count($resultado) == 0)
                break;
            $respostas = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            foreach($resultado as $linha)
                $respostas[$linha['resposta']] = $linha['total'];
            $saida[] = new RelatorioQuantitativo($pergunta->descricao, $respostas[5],$respostas[4],$respostas[3],$respostas[2],$respostas[1]);
        }
      
        return new ArrayDataProvider([
            'allModels' => $saida,
            'pagination' => false,
        ]);
    }

    public static function relatorioAvaliacao(RelatorioForm $relatorioForm)
    {
        $saida = array();

        $saida[] = new RelatorioQuantitativo('teste',12,5,3,2,1);
        return new ArrayDataProvider([
            'allModels' => $saida,
            'pagination' => false,
        ]);
    }
}