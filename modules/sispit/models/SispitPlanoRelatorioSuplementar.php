<?php

namespace app\modules\sispit\models;

use Yii;

use  dektrium\user\models\User;
use app\modules\sisrh\models\SisrhPessoa;
use app\modules\sispit\models\SispitAtividadeComplementar;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "sispit_plano_relatorio".
 *
 * @property int $id_plano_relatorio
 * @property int $user_id
 * @property int $id_ano
 * @property int $observacao_pit
 * @property int $observacao_rit
 * @property int $justificativa
 * @property string $data_homologacao_nucleo_pit
 * @property string $data_homologacao_cac_pit
 * @property string $data_preenchimento_pit 	
 * @property string $data_homologacao_nucleo_rit
 * @property string $data_homologacao_cac_rit
 * @property string $data_preenchimento_rit 	
 * @property int $status
 *
 * @property SispitAfastamentoDocente[] $sispitAfastamentoDocentes
 * @property SispitAtividadeComplementar $sispitAtividadeComplementar
 * @property SispitAtividadesAdministrativas[] $sispitAtividadesAdministrativas
 * @property SispitEnsinoComponente[] $sispitEnsinoComponentes
 * @property SispitEnsinoOrientacao[] $sispitEnsinoOrientacaos
 * @property SispitEstagioProbatorio[] $sispitEstagioProbatorios
 * @property SispitMonitoria[] $sispitMonitorias
 * @property SispitOrientacaoAcademica[] $sispitOrientacaoAcademicas
 * @property SispitParecer[] $sispitParecers
 * @property SispitParticipacaoEvento[] $sispitParticipacaoEventos
 * @property SispitPesquisaExtensao[] $sispitPesquisaExtensaos
 * @property SispitAno $id_ano
 * @property User $pessoa
 * @property SispitPublicacao[] $sispitPublicacaos
 */
class SispitPlanoRelatorioSuplementar extends SispitPlanoRelatorio
{
    
    public function getSispitAtividadeComplementar()
    {
        return $this->hasOne(SispitAtividadeComplementarSuplementar::class, ['id_plano_relatorio' => 'id_plano_relatorio']);
    }

    public function getTotal($pit_rit)
    {
        $componentes = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(ch_teorica,0) + COALESCE(ch_pratica,0) + COALESCE(ch_estagio,0))
             AS total FROM sispit_ensino_componente WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());

        if($this->sispitAtividadeComplementar != null){
            $atividadeComplementar = [
                [ 'semestre' => 1, 'total' => !$pit_rit ? 
                    $this->sispitAtividadeComplementar->ch_graduacao_sem1_pit + $this->sispitAtividadeComplementar->ch_pos_sem1_pit : 
                    $this->sispitAtividadeComplementar->ch_graduacao_sem1_rit + $this->sispitAtividadeComplementar->ch_pos_sem1_rit
                ],
            ];
            $orientacaoAcademica = [
                [ 'semestre' => 1, 'total' => 0 + (!$pit_rit ? 
                    $this->sispitAtividadeComplementar->ch_orientacao_academica_sem1_pit : $this->sispitAtividadeComplementar->ch_orientacao_academica_sem1_rit)
                ],
            ];
        }
        else{
            $atividadeComplementar = [['semestre' => 1, 'total' => 0]];
            $orientacaoAcademica = [['semestre' => 1, 'total' => 0]];
        }

        $monitoria = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_monitoria WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());

        $orientacao = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_ensino_orientacao WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());

        $pesquisaex = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_pesquisa_extensao WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());

        $administracao = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_atividades_administrativas WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());

        $afastamento = $this->formatarCHTotal(
             Yii::$app->db->createCommand("SELECT semestre, SUM(COALESCE(carga_horaria,0)) AS total FROM sispit_afastamento_docente WHERE id_plano_relatorio = $this->id_plano_relatorio AND pit_rit = $pit_rit GROUP BY semestre")->queryAll());


        $total = [
            ['semestre' => 1,
            'total' => $componentes[0]['total'] + $atividadeComplementar[0]['total'] + $orientacaoAcademica[0]['total'] + $monitoria[0]['total'] + $orientacao[0]['total'] + $pesquisaex[0]['total'] + $administracao[0]['total'] + $afastamento[0]['total'],
            ],
        ];

        return [
            'Disciplinas Ministradas' => $componentes,
            'Atividade Complementar' => $atividadeComplementar,
            'Orientação Acadêmica' => $orientacaoAcademica,
            'Orientação de Monitoria' => $monitoria,
            'Outras Orientações' => $orientacao,
            'Pesquisa e Extensão' => $pesquisaex,
            'Atividades de Administração' => $administracao,
            'Afastamento' => $afastamento,
            'Total' => $total];
    }

    /**
     * Ajusta os totais de carga horária de cada um dos itens do pit/rit para o formato:
     * [['semestre' => 1, 'total' => <valor>]]
     */
    protected function formatarCHTotal($array){
        switch(count($array)){
            case 0:
                return [['semestre' => 1, 'total' => 0]];
            case 1:
                if($array[0]['semestre'] == 1)
                    return $array;
                return [['semestre' =>  1, 'total' => 0]];
            case 2:
                return [$array[0]];
        }
    }

    public function validarCargaHoraria($pit_rit = null)
    {
        if($pit_rit === null)
            $pit_rit = $this->isRitAvailable() ? 1 : 0;
        
        $totalArray = $this->getTotal($pit_rit);

        $pessoa = SisrhPessoa::find()->where(['id_user' => $this->user_id])->one();
        $total = $totalArray['Total'];
        $limite = $pessoa->regime_trabalho;
        if($pessoa->regime_trabalho == null)
            throw new BadRequestHttpException("O seu regime de trabalho não está definido no sistema. Por favor, entre em contato com a CGDP através do e-mail cgdp.ims@ufba.br para atualizar seu cadastro.");
        if($limite > 40)
            $limite = 40;

        $componentes = $totalArray['Disciplinas Ministradas'];
        $administracao = $totalArray['Atividades de Administração'];
        $afastamento = $totalArray['Afastamento'];
        $limiteComponentes = 4;

        if($administracao[0]['total'] >= $limite - $limiteComponentes || $afastamento[0]['total'] >= $limite - $limiteComponentes ||
            (in_array($this->user_id,[97,100,152,198, 115]) && $this->id_ano == 2)) //Suellen pediu para retirar a restrição para Sóstenes, Danúsia, Daniela Goulart e Márcio Galvão no SLS 2020 pois os componentes que ele ministrou não foram cadastrados no SISCC SLS 2020, e sim em 2020.2 (residência)
            $limiteComponentes = 0;

        $atividadeComplementarLabels = [
            'ch_graduacao_sem1_pit' => 'A carga horária de graduação de Atividade Complementar',
            'ch_pos_sem1_pit' => 'A carga horária de pós-graduação de Atividade Complementar',
            'ch_graduacao_sem1_rit' => 'A carga horária de graduação de Atividade Complementar',
            'ch_pos_sem1_rit' => 'A carga horária de pós-graduação de Atividade Complementar',
            'ch_orientacao_academica_sem1_pit' => 'A carga horária de Orientação Acadêmica',
            'ch_orientacao_academica_sem1_rit' => 'A carga horária de Orientação Acadêmica',
        ];

        $atividadeComplementar = $this->sispitAtividadeComplementar;
        $atividadeComplementar->scenario = $pit_rit ? SispitAtividadeComplementar::SCENARIO_RIT : SispitAtividadeComplementar::SCENARIO_PIT;
        $atividadeComplementar->validate();
        $atvErros = $atividadeComplementar->getErrors();

        $erros = array();

        foreach($componentes as $value)
            if($value['total'] < $limiteComponentes)
                $erros[] = "A carga horária de componentes ministrados não pode ser inferior a {$limiteComponentes}.";

        foreach($atvErros as $k => $v){
            foreach($v as $erro)
                $erros[] = "{$atividadeComplementarLabels[$k]} apresentou o seguinte erro: $erro.";
        }

        foreach($total as $value)
            if($value['total'] <> $limite)
                $erros[] = "A carga horária total ({$value['total']}) não corresponde a sua carga horária semanal ($limite).";

        $this->addErrors(['id_plano_relatorio' => $erros]);

        return count($erros) == 0;
    }

    // public function planoRelatorioToArray($pit_rit = null)
    // {
    //     if($pit_rit === null)
    //         $pit_rit = $this->isRitAvailable() ? 1 : 0;

    //     $id = $this->id_plano_relatorio;
        
    //     $ensinoComponente = new SispitEnsinoComponenteSearch();
    //     $ensinoComponente->id_plano_relatorio = $id;
    //     $ensinoComponente->pit_rit = $pit_rit;
    //     $orientacaoAcademica = new SispitOrientacaoAcademicaSearch;
    //     $orientacaoAcademica->id_plano_relatorio = $id;
    //     $orientacaoAcademica->pit_rit = $pit_rit;
    //     $monitoria = new SispitMonitoriaSearch;
    //     $monitoria->id_plano_relatorio = $id;
    //     $monitoria->pit_rit = $pit_rit;
    //     $ensinoOrientacao = new SispitEnsinoOrientacaoSearch;
    //     $ensinoOrientacao->id_plano_relatorio = $id;
    //     $ensinoOrientacao->pit_rit = $pit_rit;
    //     $atividadesAdministrativas = new SispitAtividadesAdministrativasSearch();
    //     $atividadesAdministrativas->id_plano_relatorio = $id;
    //     $atividadesAdministrativas->pit_rit = $pit_rit;
    //     $afastamentoDocente = new SispitAfastamentoDocenteSearch();
    //     $afastamentoDocente->id_plano_relatorio = $id;
    //     $afastamentoDocente->pit_rit = $pit_rit;
    //     $participacaoEventos = new SispitParticipacaoEventoSearch();
    //     $participacaoEventos->id_plano_relatorio = $id;
    //     $participacaoEventos->pit_rit = $pit_rit;
    //     $pesquisaExtensao = new SispitPesquisaExtensaoSearch();
    //     $pesquisaExtensao->id_plano_relatorio = $id;
    //     $pesquisaExtensao->pit_rit = $pit_rit;
    //     $publicacao = new SispitPublicacaoSearch();
    //     $publicacao->id_plano_relatorio = $id;
    //     $publicacao->pit_rit = $pit_rit;

    //     return [
    //         'pit_rit' => $pit_rit,
    //         'model' => $this,
    //         'ensinoComponente' => $ensinoComponente,
    //         'atividadeComplementar' => $this->sispitAtividadeComplementar,
    //         'orientacaoAcademica' => $orientacaoAcademica,
    //         'monitoria'=> $monitoria,
    //         'ensinoOrientacao' => $ensinoOrientacao,
    //         'atividadesAdministrativas' =>$atividadesAdministrativas,
    //         'afastamentoDocente' =>$afastamentoDocente,
    //         'participacaoEventos' =>$participacaoEventos,
    //         'pesquisaExtensao' =>$pesquisaExtensao,
    //         'publicacao' =>$publicacao,
    //     ];
    // }
}
