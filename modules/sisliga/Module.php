<?php

namespace app\modules\sisliga;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
/**
 * sisliga module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\sisliga\controllers';

    /**
     * Tema da tela
     */
    public $skin;

    /**
     * Indica se o módulo está ativo. Este parâmetro é definido no arquivo de configuração
     * e tem o propósito de auxiliar nas manutenções dos módulos
     */
    public $ativo;

    public $menu;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if(!$this->ativo && !Yii::$app->user->can('siscatAdministrar'))
            throw new HttpException(503, 'O sistema está em manutenção. Tente novamente mais tarde.');

        $sisliga = Yii::$app->user->can('sisliga');

        if(!$sisliga) 
            throw new NotFoundHttpException(Yii::t('app',"You don't have permission to view this page."));

        //redireciona a página de erro para exibir o layout do módulo
        Yii::$app->errorHandler->errorAction = 'sisliga/default/error';

        $sisligaAdministrar = Yii::$app->user->can('sisligaAdministrar');

        $this->menu = [
            'items' => [
                [
                    'label' => 'Minhas Ligas', 'icon' => 'book', 'visible' => $sisliga, 'items' => [
                        ['label' => 'Ligas Acadêmicas', 'icon' => 'circle-o', 'url' => ['/sisliga/sisligaligaacademica']],
                        ['label' => 'Relatórios', 'icon' => 'circle-o', 'url' => ['/sisliga/sisligarelatorio']],
                    ],
                ],
                [
                    'label' => 'Comissão', 'icon' => 'graduation-cap', 'visible' => $sisligaAdministrar, 'items' => [
                        ['label' => 'Gerenciar Ligas', 'icon' => 'circle-o', 'url' => ['/sisliga/gerenciamento']],
                        ['label' => 'Gerenciar Relatórios', 'icon' => 'circle-o', 'url' => ['/sisliga/gerenciamento/relatorios']],
                        ['label' => 'Resumo', 'icon' => 'circle-o', 'url' => ['/sisliga/gerenciamento/resumo']],

                    ], 
                ],
                // [
                //     'label' => 'Relatórios do Sistema', 'icon' => 'tasks', 'visible' => $sisligaAdministrar, 'items' => [                        
                //         ['label' => 'Ligas', 'icon' => 'circle-o', 'url' => ['/sisliga/gerenciamento/relatoriosistemaligas']],
                //         ['label' => 'Participantes', 'icon' => 'circle-o', 'url' => ['/sisliga/gerenciamento/relatoriosistemaparticipantes']],
                //     ],
                // ],
                [
                    'label' => 'Parecer', 'icon' => 'check', 'visible' => $sisliga, 'url' => ['/sisliga/sisligaparecer'],
                ], 
            ]
        ];
    }
}
