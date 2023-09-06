<?php

namespace app\modules\sispit\config;


class LimiteCargaHoraria
{
    // Constante que armazena as cargas horárias máximas para cada um dos itens de preenchimento de pit/rit, com exceção de ensino_componente que só possui restrição de valor mínimo
    public const LIMITES = [
        //limites mínimos de carga horária presencial ministrados em componentes curriculares.
        //(Diretor(a) e coordenador(a) acadêmico estão isentos desse limite.)
        'ensino_componente' => [
            0 =>  4, //mínimo de 4 horas para aqueles que possuem cargos administrativos (exceto direção e coordenação acadêmica)
            20 => 10, //10h para jornada de 20 horas
            99 => 10, // 10h  ''    ''    '' dedicação exclusiva
            40 => 16, //16h  ''    ''    '' 40 horas
        ],
        //'atividade_complementar', atualmente o limite da atividade complementar é o total da carga horaria das aulas presenciais (ensino componente).
        'orientacao_academica' => ['max' => 1, 'qAlunos' => 4],//máximo de 1 hora por cada 4 alunos
        'monitoria' => 1, //valor máximo por aluno
        'ensino_orientacao' => [//Alguns dos itens desse array foram removidos, mas decidimos manter os valores correspondentes aqui para compatibilidade
            0 => 1,
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 1,
            5 => 2,
            6 => 2,
            7 => 1,
            8 => 1,
            9 => 1,
           10 => 1,
           11 => 1,
           12 => 1, 
        ], //valores máximos por aluno conforme o tipo de orientaçâo - ver: app\modules\sispit\models\SispitEnsinoOrientacao::TIPO_ORIENTACAO
        'pesquisa_extensao' => 20, //valor máximo para o total de todas as atividades de pesquisa e extensão somadas
        'atividades_administrativas' => [//valores máximos para cada um dos tipos de atividades administrativas - ver: app\modules\sispit\models\SispitAtividadesAdministrativas::TIPO_ATIVIDADE
            //Alguns dos itens desse array foram removidos, mas decidimos manter os valores correspondentes aqui para compatibilidade com os já preenchidos
            1=> 40, 2=> 10,                             //direção
            3 => 2, 4 => 2,                             //congregação
            5 => 40, 55 => 10, 6=> 2, 7 => 2,           //CAC
            8 => 20, 9 => 10, 10 => 2, 11 => 2,         //colegiado
            12 => 15, 13 => 8, 14 => 2,                 //núcleo
            15 => 20,41 => 10, 42 => 2, 43 => 2,        //copex
            16 => 15, 17 => 5,                          //CGL
            18 => 5, 19 => 5,                           //orgaos superiores ufba
            20 => 20,                                   //acessoria
            21 => 10, 22 => 5, 23 => 2,                 //CEP
            24 => 10, 25 => 5, 26 => 2,                 //CEUA
            27 => 10, 28 => 5, 29 => 2,                 //CAVI
            30 => 10, 31 => 5, 32 => 2,                 //CIBIO
            45 => 10, 46 => 5, 47 => 2,                 //formação docente
            48 => 10, 49 => 5, 50 => 2,                 //pit rit
            51 => 10, 52 => 2,                           //progressao docente
            53 => 2, 54 => 1,                           //probatório docente
            33 => 20,                                   //COAE
            34 => 2, 35 => 1, 36 => 1,                  //grupos de trabalho
            37 => 2, 38 => 1, 39 => 1,                  //NDE
            40 => 4,                                    //biotério
            44 => 2,                                    //outros
        ],
    ];
}