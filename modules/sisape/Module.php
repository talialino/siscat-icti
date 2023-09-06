<?php

namespace app\modules\sisape;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
/**
 * sisape module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\sisape\controllers';

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

        $sisape = Yii::$app->user->can('sisape');

        if(!$sisape) 
            throw new NotFoundHttpException(Yii::t('app',"You don't have permission to view this page."));

        //redireciona a página de erro para exibir o layout do módulo
        Yii::$app->errorHandler->errorAction = 'sisape/default/error';

        $sisapeAdministrar = Yii::$app->user->can('sisapeAdministrar');
        $sisapeGerenciarNucleo = Yii::$app->user->can('sisapeGerenciarNucleo');

        $this->menu = [
            'items' => [
                [
                    'label' => 'Meus Projetos', 'icon' => 'book', 'visible' => $sisape, 'items' => [
                        ['label' => 'Projetos', 'icon' => 'circle-o', 'url' => ['/sisape/sisapeprojeto']],
                        ['label' => 'Relatórios', 'icon' => 'circle-o', 'url' => ['/sisape/sisaperelatorio']],
                    ],
                ],
                [
                    'label' => 'COPEX', 'icon' => 'graduation-cap', 'visible' => $sisapeAdministrar, 'items' => [
                        ['label' => 'Gerenciar Projetos', 'icon' => 'circle-o', 'url' => ['/sisape/gerenciamentocopex']],
                        //['label' => 'Projetos Importados', 'icon' => 'circle-o', 'url' => ['/sisape/gerenciamentocopex/projetosimportados']],
                        ['label' => 'Gerenciar Relatórios', 'icon' => 'circle-o', 'url' => ['/sisape/gerenciamentocopex/relatorios']],
                        //['label' => 'Relatórios Importados', 'icon' => 'circle-o', 'url' => ['/sisape/gerenciamentocopex/relatoriosimportados']],
                        ['label' => 'Resumo', 'icon' => 'circle-o', 'url' => ['/sisape/gerenciamentocopex/resumo']],

                    ], 
                ],
                [
                    'label' => 'Relatórios do Sistema', 'icon' => 'tasks', 'visible' => $sisapeAdministrar, 'items' => [                        
                        ['label' => 'Projetos', 'icon' => 'circle-o', 'url' => ['/sisape/gerenciamentocopex/relatoriosistemaprojetos']],
                        ['label' => 'Participantes', 'icon' => 'circle-o', 'url' => ['/sisape/gerenciamentocopex/relatoriosistemaparticipantes']],
                    ],
                ],
                [
                    'label' => 'Núcleo Acadêmico', 'icon' => 'users', 'visible' => $sisapeGerenciarNucleo, 'items' => [
                        ['label' => 'Projetos', 'icon' => 'circle-o', 'url' => ['/sisape/gerenciarnucleo']],
                        ['label' => 'Relatórios', 'icon' => 'circle-o', 'url' => ['/sisape/gerenciarnucleo/relatorios']],
                    ],
                ],
                /* [
                    'label' => 'Parecer', 'icon' => 'check', 'visible' => $sisape, 'url' => ['/sisape/sisapeparecer'],
                ], */
            ]
        ];
    }
}
