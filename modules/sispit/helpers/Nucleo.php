<?php
namespace app\modules\sispit\helpers;

use \Yii;
use yii\db\Query;

/**
 * Classe que contém métodos para validação de acesso de núcleo
 */

class Nucleo{

    /**
     * Verifica se o autor do plano_relatorio faz parte do núcleo.
     * @param int $plano id_plano_relatorio que deseja ser verificado
     * @param int $nucleo id_setor correspondente ao núcleo
     * @return boolean
     */
    public static function validarNucleoPlanoRelatorio($plano, $nucleo){

        $query = new Query;        
        $query->select('p.nome')
            ->from('sisrh_pessoa p')
            ->innerJoin('sisrh_setor_pessoa sp',"sp.id_pessoa = p.id_pessoa AND sp.id_setor = $nucleo")
            ->innerJoin('sispit_plano_relatorio pr',
                "p.id_user = pr.user_id AND pr.id_plano_relatorio = $plano");
        
        return $query->exists();
    }
}