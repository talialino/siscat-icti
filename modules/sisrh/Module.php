<?php

namespace app\modules\sisrh;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
/**
 * sisrh module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\sisrh\controllers';
    
    /**
     * Tema da tela
     */
    public $skin;

    /**
     * Indica se o módulo está ativo. Este parâmetro é definido no arquivo de configuração
     * e tem o propósito de auxiliar nas manutenções dos módulos
     */
    public $ativo;

    /**
     * Menu lateral do sistema
     */
    public $menu;

    /**
     * sisrh_setor(s) que tem permissão de administrador do módulo
     */
    public $admins;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if(!$this->ativo && !Yii::$app->user->can('siscatAdministrar'))
            throw new HttpException(503, 'O sistema está em manutenção. Tente novamente mais tarde.');

        if(!Yii::$app->user->can('sisrh'))
            throw new NotFoundHttpException(Yii::t('app',"You don't have permission to view this page."));
        
        //redireciona a página de erro para exibir o layout do módulo
        Yii::$app->errorHandler->errorAction = 'sisrh/default/error';

        $admin = Yii::$app->user->can('sisrhAdministrar');
        $relatorios = Yii::$app->user->can('sisrhreports');
        $this->menu =
        [
            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            'items' => [
                ['label' => Yii::t('app','People'), 'icon' => 'user', 'url' => ['/sisrh/sisrhpessoa'], 'visible' => Yii::$app->user->can('sisrhpessoa')],

                ['label' => Yii::t('app','Occurrences'), 'icon' => 'plane', 'visible' => $admin,
                    'items' => [
                        ['label' => 'Gerenciar', 'icon' => 'circle-o', 'url' => ['/sisrh/sisrhafastamento']],
                        ['label' => 'Tipos de ocorrência', 'icon' => 'circle-o', 'url' => ['/sisrh/sisrhocorrencia'],]
                    ],
                ],
                ['label' => Yii::t('app','Sectors'), 'icon' => 'university', 'url' => ['/sisrh/sisrhsetor'], 'visible' => $relatorios],
                ['label' => Yii::t('app','Comissões'), 'icon' => 'university', 'url' => ['/sisrh/sisrhcomissao'], 'visible' => $relatorios],
                ['label' => Yii::t('app','Office'), 'icon' => 'tasks', 'url' => ['/sisrh/sisrhcargo'], 'visible' => $admin],
                ['label' => Yii::t('app','Category'), 'icon' => 'tags', 'url' => ['/sisrh/sisrhcategoria'], 'visible' => Yii::$app->user->can('siscatAdministrar')],
                ['label' => Yii::t('app','Functional Class'), 'icon' => 'building', 'url' => ['/sisrh/sisrhclassefuncional'], 'visible' => $admin],
                ['label' => 'Contatos', 'icon' => 'address-book', 'url' => ['/sisrh/reports/contatos'], 'visible' => Yii::$app->user->can('sisrh')],
                ['label' => Yii::t('app','Reports'), 'icon' => 'file-text', 'visible' => $relatorios,
                    'items' => [
                        ['label' => 'Ocorrências', 'icon' => 'circle-o', 'url' => ['/sisrh/reports/ocorrencias']],
                    ],
                ],
                ['label' => 'Membros Automáticos', 'icon' => 'users', 'url' => ['/sisrh/sisrhsetormembroautomatico'], 'visible' => $admin],
            ],
        ];
    }
}
