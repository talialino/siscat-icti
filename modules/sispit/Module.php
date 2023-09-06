<?php

namespace app\modules\sispit;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use app\modules\sispit\models\SispitPlanoRelatorio;
/**
 * sispit module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\sispit\controllers';

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
    * Título da barra da esquerda
    */
    public $left;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if(!$this->ativo && !Yii::$app->user->can('siscatAdministrar'))
            throw new HttpException(503, 'O sistema está em manutenção. Tente novamente mais tarde.');

        if(Yii::$app->user->isGuest || !Yii::$app->user->can('sispit'))
            throw new NotFoundHttpException(Yii::t('app',"You don't have permission to view this page."));

        //redireciona a página de erro para exibir o layout do módulo
        Yii::$app->errorHandler->errorAction = 'sispit/default/error';

        $session = Yii::$app->session;

        $ano = $session->get('siscat_ano') ? $session->get('siscat_ano') : false;

        $this->left = "$this->id ".($ano ? $ano->string : '');

        $plano = $ano ? SispitPlanoRelatorio::getPlanoRelatorio(Yii::$app->user->id, $ano->id_ano) : null;

        $permissões = [
            'sispitDocente' => $ano && Yii::$app->user->can('sispitDocente',['id' => Yii::$app->user->id]),
            'gerenciarNucleo' => $ano && Yii::$app->user->can('sispitGerenciarNucleo'),
            'admin' => $ano && Yii::$app->user->can('sispitAdministrar'),
            'gerenciarComissao' => Yii::$app->user->can('ComissaoPitRit'),
        ];

        $id_plano_relatorio = ($plano != null ? $plano->id_plano_relatorio : null);

        $items = [
            ['label' => 'Ensino', 'icon' => 'circle-o', 'url' => ['/sispit/sispitplanorelatorio/ensino', 'id' => $id_plano_relatorio]],
            ['label' => 'Orientação', 'icon' => 'circle-o', 'url' => ['/sispit/sispitplanorelatorio/orientacao', 'id' => $id_plano_relatorio]],
            ['label' => 'Pesquisa/Extensão', 'icon' => 'circle-o', 'url' => ['/sispit/sispitpesquisaextensao', 'id' => $id_plano_relatorio]],
            ['label' => 'Atividades de Administração', 'icon' => 'circle-o', 'url' => ['/sispit/sispitatividadesadministrativas', 'id' => $id_plano_relatorio]],
            ['label' => 'Ocorrências', 'icon' => 'circle-o', 'url' => ['/sispit/sispitafastamentodocente', 'id' => $id_plano_relatorio]],
            ['label' => 'Participação em Eventos', 'icon' => 'circle-o', 'url' => ['/sispit/sispitparticipacaoevento', 'id' => $id_plano_relatorio]],
            ['label' => 'Publicação', 'icon' => 'circle-o', 'url' => ['/sispit/sispitpublicacao', 'id' => $id_plano_relatorio]],
            ['label' => 'Observações', 'icon' => 'circle-o', 'url' => ['/sispit/sispitplanorelatorio/observacoes', 'id' => $id_plano_relatorio]],
            ['label' => 'Visualizar/Submeter', 'icon' => 'circle-o', 'url' => ['/sispit/sispitplanorelatorio/view', 'id' => $id_plano_relatorio]],
        ];
       
        $this->menu =
        [
            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            'items' => [
                ['label' => 'Criar PIT', 'icon' => 'book', 'url' => ['/sispit/sispitplanorelatorio/create'], 'visible' => $plano == null && $permissões['sispitDocente']],
                ['label' => 'PIT', 'icon' => 'book', 'visible' => $plano != null && $permissões['sispitDocente'] && !$plano->isRitAvailable(),
                    'items' => $items, 'options' => ['class' => 'menu-open'],
                ],
                ['label' => 'Visualizar PIT', 'icon' => 'book', 'url' => ['/sispit/sispitplanorelatorio/view', 'id' => $id_plano_relatorio, 'pit_rit' => 0], 'visible' => $plano != null && $plano->isRitAvailable()],
                 ['label' => 'Criar RIT', 'icon' => 'tasks', 'url' => ['/sispit/sispitplanorelatorio/create'], 'visible' => $plano != null && $plano->status == 9 && $permissões['sispitDocente']],
                 ['label' => 'RIT', 'icon' => 'tasks', 'visible' => $plano != null && $plano->isRitAvailable() && $permissões['sispitDocente'],
                     'items' => $items, 'options' => ['class' => 'menu-open'],
                 ],
                ['label' => 'Parecer', 'icon' => 'check', 'visible' => $permissões['sispitDocente'],
                    'items' => [
                        ['label' => 'Meus Pareceres', 'icon' => 'circle-o', 'url' => ['/sispit/sispitparecer'], ],
                        ['label' => 'Pareceres recebidos', 'icon' => 'circle-o', 'url' => ['/sispit/sispitparecer/recebido', 'id' => $id_plano_relatorio], 'visible' => $plano != null && $permissões['sispitDocente']],
                    ], 'options' => ['class' => 'menu-open'],
                ],
                ['label' => 'Gerenciar Núcleo', 'icon' => 'users', 'url' => ['/sispit/gerenciarnucleo'], 'visible' => $permissões['gerenciarNucleo']],
                ['label' => 'Comissão PIT/RIT', 'icon' => 'users', 'url' => ['/sispit/gerenciarcomissao'], 'visible' => $permissões['gerenciarComissao']],
                ['label' => 'Coordenação Acadêmica', 'icon' => 'graduation-cap', 'visible' => $permissões['admin'],
                    'items' => [
                        ['label' => 'Gerenciamento', 'icon' => 'circle-o', 'url' => ['/sispit/gerenciamentocac'],],
                        ['label' => 'Resumo', 'icon' => 'circle-o', 'url' => ['/sispit/gerenciamentocac/resumo'],],
                        ['label' => 'Gerenciar ano', 'icon' => 'circle-o', 'url' => ['/sispit/sispitano'],]
                    ], 'options' => ['class' => 'menu-open'],
                ],
                ['label' => 'Selecionar Ano', 'icon' => 'calendar', 'url' => ['/sispit/sispitano/selecionarano'],],
            ],
            'submenuTemplate' => "\n<ul class='treeview-menu' style='display:block;'>\n{items}\n</ul>\n",
        ];
    }
}
