<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use yii\db\Query;
use app\modules\sisrh\models\SisrhPessoa;

/**
* Faz a relação entre permissão e setor
*/

class PermissionGroupRule extends Rule
{
   public $name = 'permissionGroup';

   /**
    * @param string|int $user the user ID.
    * @param Item $item the role or permission that this rule is associated with
    * @param array $params parameters passed to ManagerInterface::checkAccess().
    * @return bool a value indicating whether the rule permits the role or permission it is associated with.
    */
   public function execute($user, $item, $params)
   {
       if (!Yii::$app->user->isGuest) {
           $query = new Query;
           $query->select('p.id_pessoa')->from('sisrh_pessoa p, sisrh_setor_pessoa sp, auth_item_sisrh_setor a')->where(
           "a.name = '{$item->name}' AND a.id_setor = sp.id_setor AND a.funcao = sp.funcao AND sp.id_pessoa = p.id_pessoa AND p.id_user = $user");
           
           return $query->exists();
       }
       return false;
   }
}
