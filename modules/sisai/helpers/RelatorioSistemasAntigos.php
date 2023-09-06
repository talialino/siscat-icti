<?php

namespace app\modules\sisai\helpers;

use app\modules\sisai\models\RelatorioForm;
use app\modules\sisai\models\RelatorioQuantitativo;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Connection;
use yii\db\Query;


class RelatorioSistemasAntigos
{

    public const CONVERSAO_ID_PESSOA = [79 => 357,
        80 => 257, 81 => 1, 82 => 2, 305 => false, 83 => 282, 310 => false, 84 => 3,
        85 => 5, 86 => 6, 87 => 7, 88 => 8, 89 => 10, 90 => 9, 263 => false, 316 => false,
        91 => 11, 92 => 12, 93 => 301, 94 => 13, 95 => 14, 238 => 375, 96 => 233, 273 => false,
        97 => 218, 98 => 15, 99 => 280, 297 => false, 225 => 372, 260 => false, 100 => 17,
        101 => 285, 262 => false, 301 => false, 102 => 248, 298 => false, 103 => 332, 104 => 19,
        105 => 20, 251 => [336,389], 106 => 22, 302 => false, 107 => 23, 108 => 24, 109 => 182,
        110 => 25, 111 => 26, 112 => 354, 113 => 355, 289 => false, 114 => 27, 115 => 28, 116 => 30,
        117 => 279, 312 => false, 118 => 31, 119 => 32, 224 => 373, 120 => 249, 121 => 323, 122 => 250,
        123 => 33, 237 => 378, 124 => 303, 125 => 34, 275 => 272, 126 => 268, 127 => 35, 128 => 36,
        279 => false, 129 => 294, 288 => false, 131 => 295, 132 => 37, 133 => 38, 134 => 367, 135 => [286,390],
        136 => 39, 137 => 252, 138 => 234, 139 => 41, 140 => 183, 299 => false, 141 => 42, 241 => 376,
        142 => 43, 143 => 44, 144 => 330, 291 => false, 293 => false, 145 => 251, 146 => 274, 147 => 256,
        264 => false, 148 => 358, 149 => 45, 150 => 46, 161 => false, 306 => false, 151 => 47, 152 => 48,
        153 => 49, 278 => 307, 154 => 198, 155 => 51, 156 => 52, 157 => 53, 8 => 54, 285 => false, 159 => 55,
        282 => false, 162 => 56, 163 => 180, 164 => 166, 165 => 284, 286 => false, 166 => 58, 167 => 275,
        168 => 59, 169 => 325, 170 => false, 171 => 258, 172 => 60, 173 => 61, 174 => 308, 175 => 62, 176 => 63,
        177 => 64, 178 => 269, 179 => 67, 180 => 65, 181 => 66, 182 => false, 184 => 322, 185 => 70, 304 => 299,
        186 => 335, 187 => 297, 284 => false, 188 => 72, 189 => 73, 313 => false, 190 => 74, 191 => 75,
        240 => 377, 192 => 76, 193 => 77, 261 => false, 303 => false, 194 => 78, 195 => 80, 196 => 79,
        197 => 306, 315 => false, 198 => 81, 199 => 310, 200 => 337, 201 => 82, 287 => false, 202 => 83,
        203 => 254, 204 => 84, 205 => 324, 206 => 181, 207 => 278, 208 => 179, 209 => 356,
    ];

    public const CONVERSAO_ID_COLEGIADO = [
        11 => 1,    //	Biotecnologia
        12 => 2,    //	Ciências Biológicas
        13 => 3,    //	Enfermagem
        14 => 4,    //	Farmácia
        15 => 11,   //	Medicina
        16 => 5,    //	Nutrição
        17 => 6,    //	Psicologia
        18 => [8,9],//	Ciências Fisiológicas
        19 => 7,    //	Biociências
        20 => 13,   //	Psicologia da Saúde
        21 => 12,   //	Saúde Coletiva
        22 => 10,   //  Residência Multiprofissional em Urgência
    ];

    public static function relatorioAvaliacaoDocente(RelatorioForm $relatorioForm)
    {
        $cc = explode('-',$relatorioForm->componente_colegiado);
        
        $perguntas = [];
        $saida = [];

        $idPessoa = ($relatorioForm->id_semestre >= 18) ? $relatorioForm->id_pessoa : self::CONVERSAO_ID_PESSOA[$relatorioForm->id_pessoa];
        if($idPessoa)
        {
            $dbantigo = require dirname(__FILE__).'/../../../config/'.(($relatorioForm->id_semestre >= 18) ? 'sisaidb.php' : 'ims2db.php');
            $connection = new Connection($dbantigo);
            $connection->open();

            $perguntas = ($relatorioForm->id_semestre < 18) ? [
                26	=> 'Apresentou e cumpriu o plano de ensino (programa, objetivos, metodologia, cronograma e bibliografia)?',
                27	=> 'Desenvolveu o conteúdo com clareza e objetividade fazendo uso de metodologia de ensino satisfatória?',
                28	=> 'Demonstrou dominar o conteúdo programático?',
                29	=> 'Propiciou a participação dos alunos durante as aulas (teóricas e/ou práticas)?',
                30	=> 'Diversificou o modo de exposição do conteúdo das aulas (material didático, dinâmicas, seminários, debates e aulas de campo)?',
                31	=> 'Estimulou os alunos a estabelecerem relações do conteúdo estudado com o de outras disciplinas correlatas e/ou com as atividades profissionais?',
                32	=> 'Foi disponível no atendimento aos alunos durante e após as aulas (teóricas e/ou práticas)?',
                33	=> 'Utilizou métodos de avaliações (provas, seminários, discussões, apresentações, trabalhos em comunidade, entre outros) adequados à proposta da disciplina?',
                34	=> 'Exigiu nas avaliações conteúdos relacionado com os desenvolvidos nas aulas?',
                35	=> 'Esclareceu eventuais dúvidas sobre os resultados das avaliações?',
                36	=> 'Levou em consideração as críticas e sugestões dos alunos quando possível?',
                37	=> 'Atentou-se para a frequência e a pontualidade dos alunos?',
                39	=> 'Foi pontual e frequente nas aulas?',
                40	=> 'Manteve postura ética e profissional em sala de aula?',
            ]:[
                97	=> 'Apresentou e cumpriu o plano de ensino (programa, objetivos, metodologia, cronograma e bibliografia)?',
                98	=> 'Desenvolveu o conteúdo com clareza e objetividade fazendo uso de metodologia de ensino adequado?',
                99	=> 'Demonstrou dominar o conteúdo programático?',
                100 => 'Propiciou a participação dos alunos durante as aulas (teóricas e/ou práticas)? (Entenda "aulas práticas" como as atividades assim designadas pelo professor)',
                101 => 'Diversificou o modo de exposição do conteúdo das aulas (material didático, dinâmicas, seminários, debates, fóruns de discussão, entre outros)? (Entenda "aulas" como atividades desenvolvidas no AVA. Entenda “AVA”-Ambiente Virtual de Aprendizagem, como qualquer ambiente online/plataforma onde ocorre interação online entre docente e aluno)',
                102 => 'Estimulou os alunos a estabelecerem relações do conteúdo estudado com o de outros componentes curriculares correlatos e/ou com as atividades profissionais?',
                103 => 'Foi disponível no atendimento aos alunos durante e após as aulas (teóricas e/ou práticas)?',
                104 => 'Utilizou métodos de avaliações (provas, seminários, discussões, apresentações, entre outros) adequados à proposta do componente curricular? (Entenda "avaliações" como as atividades avaliativas desenvolvidas no AVA do componente curricular)',
                105 => 'Exigiu nas avaliações conteúdos relacionados com os desenvolvidos nas aulas?',
                106 => 'Esclareceu eventuais dúvidas sobre os resultados das avaliações?',
                107 => 'Levou em consideração as críticas e sugestões dos alunos quando possível?',
                108 => 'Atentou para a frequência e a pontualidade dos alunos? (Entenda "frequência e pontualidade" como ter cumprido dentro dos prazos as atividades propostas no componente curricular)',
                109 => 'Foi pontual e frequente nas aulas? (Entenda "pontual e frequente" como ter cumprido os prazos de disponibilização do link das aulas/recursos/atividades propostas no componente curricular)',
                110 => 'Manteve postura ética e profissional em sala de aula? (Entenda "sala de aula" como o Ambiente Virtual de Aprendizagem-AVA)',
                111 => 'Disponibilizou Ambiente Virtual de Aprendizagem (AVA) para realização das atividades do componente curricular? (Entenda “AVA” como qualquer ambiente online/plataforma onde ocorre interação online entre docente e aluno)',
                112 => 'No AVA, ocorreu a disponibilização de recursos/ferramentas para o desenvolvimento de atividades (síncronas e/ou assíncronas)? (Entenda "recursos/ferramentas" como o material disponibilizado pelo professor; Entenda “síncronas” e “assíncronas” como as aulas/atividades/tarefas desenvolvidas pelo aluno com e sem a presença simultânea do professor, respectivamente)',
                113 => 'No AVA, ocorreu diversificação dos recursos/ferramentas para o desenvolvimento de atividades (síncronas e/ou assíncronas)?',
                114 => 'No AVA, ocorreu desenvolvimento de atividades assíncronas? (Entenda "atividades assíncronas" como as atividades desenvolvidas pelo aluno na ausência do professor)',
                115 => 'No AVA, ocorreu desenvolvimento de atividades síncronas? (Entenda "atividades síncronas" como as atividades desenvolvidas pelo aluno com a presença simultânea do professor)',
                120 => 'As atividades avaliativas foram coerentes com os recursos/ferramentas disponibilizadas no componente curricular?',

            ];


            foreach($perguntas as $idPergunta => $pergunta){
                    
                $query = new Query();
                $resultado = $query->select('r.ds_valor as resposta, count(r.cd_robjetiva) as total')
                    ->from('SISAI_RObjetiva r, SISAI_Avaliacao a, SISAI_AAPCC ap')
                    ->where('r.cd_pergunta = :pergunta and r.cd_avaliacao = a.cd_avaliacao and a.cd_semestre = :semestre and a.nr_status > a.nr_etapas
                        and ap.id_pessoa=:pessoa and ap.cd_ccurricular=:ccurricular and r.cd_aapcc = ap.cd_aapcc',[
                        ':semestre' => $relatorioForm->id_semestre,':ccurricular' => $cc[0],':pessoa' => $idPessoa,
                        ':pergunta' => $idPergunta
                    ])->groupBy('resposta')->createCommand($connection)->queryAll();
               
                if(count($resultado) == 0)
                    break;
                $respostas = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                foreach($resultado as $linha)
                    $respostas[$linha['resposta']] = $linha['total'];
                $saida[] = new RelatorioQuantitativo($pergunta, $respostas[5],$respostas[4],$respostas[3],$respostas[2],$respostas[1]);
            }
            $connection->close();
        }
      
        return [new ArrayDataProvider([
            'allModels' => $saida,
            'pagination' => false,
        ]), ($relatorioForm->id_semestre < 18) ? 2 : 16];
    }

    public static function listaComponentesColegiados($id_pessoa, $id_semestre)
    {
        if($id_pessoa == null || $id_semestre == null)
            return array();

        if($id_semestre < 18)
            $id_pessoa = self::CONVERSAO_ID_PESSOA[$id_pessoa];
        if(!$id_pessoa)
            return array();

        $dbantigo = require dirname(__FILE__).'/../../../config/'.(($id_semestre >= 18) ? 'sisaidb.php' : 'ims2db.php');
        $connection = new Connection($dbantigo);
        $connection->open();

        $query = new Query();
        $query->select('c.cd_ccurricular,ds_codigo,nm_ccurricular')
            ->from('SISCC_CCurricular c, SISCC_PCCurricular p, SISCC_PCCurricular_Professor pp')
            ->where('c.cd_ccurricular = p.cd_ccurricular and p.cd_pccurricular = pp.cd_pccurricular
                and p.cd_semestre = :semestre and pp.id_pessoa = :professor',
                [':semestre' => $id_semestre, ':professor' => $id_pessoa]);
        $resultados = $query->createCommand($connection)->queryAll();
        $saida = array();
        foreach($resultados as $linha)
        {
            $saida["{$linha['cd_ccurricular']}-1"] = "{$linha['ds_codigo']} - {$linha['nm_ccurricular']}";
        }
        $connection->close();
        return $saida;
    }

    public static function relatorioAvaliacaoDiscente(RelatorioForm $relatorioForm)
    {
        $perguntas = [];
        $saida = [];

        $idSetor = ($relatorioForm->id_semestre >= 18) ? $relatorioForm->id_setor : self::CONVERSAO_ID_COLEGIADO[$relatorioForm->id_setor];
        if($idSetor)
        {
            $dbantigo = require dirname(__FILE__).'/../../../config/'.(($relatorioForm->id_semestre >= 18) ? 'sisaidb.php' : 'ims2db.php');
            $connection = new Connection($dbantigo);
            $connection->open();
            $perguntas = ($relatorioForm->id_semestre < 18) ? [
                57 => 'Foi pontual e freqüente às aulas?',
                58 => 'Comportou-se de maneira adequada, não participando de atividades paralelas (conversas, trabalhos de outras disciplinas, etc) durante a aula?',
                59 => 'Demonstrou interesse e participou das aulas?',
                60 => 'Relacionou-se de forma adequada com você?',
            ]:[
                147 =>	'Foi pontual e frequente às aulas? (Entenda "pontual e frequente" como ter cumprido dentro dos prazos as atividades propostas no componente curricular)',
                148 =>	'Comportou-se de maneira adequada, não participando de atividades paralelas (conversas, trabalhos de outras disciplinas, etc.) durante a aula? (Entenda "aula" como as atividades síncronas desenvolvidas entre alunos e professores)',
                149 =>	'Demonstrou interesse e participou nas aulas? (Entenda "aula" como as atividades desenvolvidas no Ambiente Virtual de Aprendizagem- AVA)',
                150 =>	'Relacionou-se de forma adequada com você?',
                151 =>	'Demonstrou interesse e participou nas atividades assíncronas? (Entenda "atividades assíncronas" como as atividades desenvolvidas pelo aluno sem a presença simultânea do professor)',
                152 =>	'Foi pontual na entrega das atividades assíncronas?',
                153 =>	'Demonstrou interesse e participou das atividades síncronas? (Entenda "atividades síncronas" como as atividades desenvolvidas pelo aluno com a presença simultânea do professor)',
                154 =>	'Foi pontual nas atividades síncronas? (Entenda "pontual" como acessar as atividades síncronas nos momentos agendados)',
            ];

            foreach($perguntas as $idPergunta => $pergunta){
                    
                $query = new Query();
                $resultado = $query->select('r.ds_valor as resposta, count(r.cd_robjetiva) as total')
                    ->from('SISAI_RObjetiva r, SISAI_Avaliacao a, SISAI_AAPCC ap')
                    ->where('r.cd_pergunta = :pergunta and r.cd_avaliacao = a.cd_avaliacao and a.cd_semestre = :semestre and a.nr_status > a.nr_etapas
                        and ap.cd_colegiado=:colegiado and r.cd_aapcc = ap.cd_aapcc and a.cd_avaliacao = ap.cd_avaliacao',[
                        ':semestre' => $relatorioForm->id_semestre,':colegiado' => $idSetor, ':pergunta' => $idPergunta
                    ])->groupBy('resposta')->createCommand($connection);
                
                $resultado = $resultado->queryAll();
               
                if(count($resultado) == 0)
                    break;
                $respostas = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                foreach($resultado as $linha)
                    $respostas[$linha['resposta']] = $linha['total'];
                $saida[] = new RelatorioQuantitativo($pergunta, $respostas[5],$respostas[4],$respostas[3],$respostas[2],$respostas[1]);
            }
            $connection->close();
        }
        return new ArrayDataProvider([
            'allModels' => $saida,
            'pagination' => false,
        ]);
    }
}