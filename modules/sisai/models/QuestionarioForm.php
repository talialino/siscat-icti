<?php

namespace app\modules\sisai\models;

use Throwable;
use Yii;
use yii\base\Model;


class QuestionarioForm extends Model{
    
    public $questionario;
    public $avaliacao;
    public $alunoComponenteCurricular = false;
    public $professorComponenteCurricular = false;
    public $errosCarregamentoQuestionario = false;
    public $subtitulo = '';


    public function __construct($id_questionario,$config=[])
    {
        parent::__construct($config);
        
        $this->questionario = SisaiQuestionario::findOne($id_questionario);
        if($this->alunoComponenteCurricular != null)
            $this->subtitulo = "{$this->alunoComponenteCurricular->setor->colegiado} - 
                {$this->alunoComponenteCurricular->componenteCurricular->codigo_componente} - {$this->alunoComponenteCurricular->componenteCurricular->nome} -
                {$this->alunoComponenteCurricular->pessoa->nome}";
        if($this->professorComponenteCurricular != null)
            $this->subtitulo = "{$this->professorComponenteCurricular->setor->colegiado} - 
                {$this->professorComponenteCurricular->componenteCurricular->codigo_componente} -
                {$this->professorComponenteCurricular->componenteCurricular->nome}";
    }

    public function getTitulo()
    {
        return $this->questionario->titulo;
    }

    public function getTipoQuestionario()
    {
        return $this->questionario->tipo_questionario;
    }

    public function getGrupoPerguntas()
    {
        return $this->questionario->sisaiGrupoPerguntas;
    }

    public function carregarQuestionario($respostas)
    {
        $contador = 0;
        $erros = array();
        $modeloRespostas = array();
        
        foreach($this->grupoPerguntas as $grupo)
            foreach($grupo->sisaiPerguntas as $pergunta)
            {
                $contador++;
                if(!isset($respostas["pergunta_$pergunta->id_pergunta"]))
                {
                    $erros[] = "A questão $contador é obrigatória";
                    continue;
                }
                if($pergunta->tipo_pergunta == SisaiPergunta::ABERTA && strlen(trim($respostas["pergunta_$pergunta->id_pergunta"])) == 0)
                    continue;
                switch($this->avaliacao->tipo_avaliacao)
                {
                    case 0:
                        $modeloRespostas[$contador] = ($pergunta->tipo_pergunta == SisaiPergunta::ABERTA || $pergunta->tipo_pergunta == SisaiPergunta::MULTIPLA_ESCOLHA) ?
                            new SisaiAlunoRespostaSubjetiva() : new SisaiAlunoRespostaObjetiva();
                    break;
                    case 1:
                        $modeloRespostas[$contador] = ($pergunta->tipo_pergunta == SisaiPergunta::ABERTA || $pergunta->tipo_pergunta == SisaiPergunta::MULTIPLA_ESCOLHA) ?
                            new SisaiProfessorRespostaSubjetiva() : new SisaiProfessorRespostaObjetiva();
                    break;
                    case 2:
                        $modeloRespostas[$contador] = ($pergunta->tipo_pergunta == SisaiPergunta::ABERTA || $pergunta->tipo_pergunta == SisaiPergunta::MULTIPLA_ESCOLHA) ?
                            new SisaiTecnicoRespostaSubjetiva() : new SisaiTecnicoRespostaObjetiva();
                    break;
                }
                $modeloRespostas[$contador]->id_avaliacao = $this->avaliacao->id_avaliacao;
                $modeloRespostas[$contador]->id_pergunta = $pergunta->id_pergunta;
                $modeloRespostas[$contador]->resposta = $respostas["pergunta_$pergunta->id_pergunta"];
                if($this->alunoComponenteCurricular)
                    $modeloRespostas[$contador]->id_aluno_componente_curricular = $this->alunoComponenteCurricular->id_aluno_componente_curricular;
                if($this->professorComponenteCurricular)
                    $modeloRespostas[$contador]->id_professor_componente_curricular = $this->professorComponenteCurricular->id_professor_componente_curricular;
                
            }
        if(count($erros) > 0)
        {
            $this->errosCarregamentoQuestionario = $erros;
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            foreach($modeloRespostas as $resposta)
            {
                if(!$resposta->save()){
                    $this->errosCarregamentoQuestionario = $resposta->getErrorSummary(true);
                    $transaction->rollBack();
                    return false;
                }
            }
            if($this->alunoComponenteCurricular)
            {
                $this->alunoComponenteCurricular->concluida = 1;
                if(!$this->alunoComponenteCurricular->save()){
                    $this->errosCarregamentoQuestionario = $resposta->getErrorSummary(true);
                    $transaction->rollBack();
                    return false;
                }
            }
            if($this->professorComponenteCurricular)
            {
                $this->professorComponenteCurricular->concluida = 1;
                if(!$this->professorComponenteCurricular->save()){
                    $this->errosCarregamentoQuestionario = $resposta->getErrorSummary(true);
                    $transaction->rollBack();
                    return false;
                }
            }
            if(!$this->avaliacao->atualizarSituacao()){
                $this->errosCarregamentoQuestionario = $this->avaliacao->getErrorSummary(true);
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        }catch(Throwable $e)
        {
            $transaction->rollBack();
            $this->errosCarregamentoQuestionario[] = $e->getMessage();
            return false;
        }
    }
}