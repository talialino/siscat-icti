<?php

namespace app\modules\sisai;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
/**
 * sisai module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\sisai\controllers';
    


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

    public $left;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $siscatAdmin = Yii::$app->user->can('siscatAdministrar');

        if(!$this->ativo && !$siscatAdmin)
            throw new HttpException(503, 'O sistema está em manutenção. Tente novamente mais tarde.');

        $sisai = Yii::$app->user->can('sisai');

        if(!$sisai) 
            throw new NotFoundHttpException(Yii::t('app',"You don't have permission to view this page."));
        
        //redireciona a página de erro para exibir o layout do módulo
        Yii::$app->errorHandler->errorAction = 'sisai/default/error';
        
        $sisaiAluno = Yii::$app->user->can('sisaiAlunoGerenciar');
        $sisaiAdmin = Yii::$app->user->can('sisaiAdministrar');

        
        $periodoAvaliacao = Yii::$app->session->get('siscat_periodo_avaliacao', false);

        if($periodoAvaliacao)
            $this->left = "$this->id - {$periodoAvaliacao->semestre->string}";

        $this->menu = [
            'items' => [
                [
                    'label' => 'Avaliação', 'icon' => 'edit' , 'url' => ['/sisai/default/avaliacao'],
                        'visible' => $periodoAvaliacao,
                ],
                [
                    'label' => 'Relatórios', 'icon' => 'tasks' , 'items' => [
                        [
                            'label' => 'Docente pelos discentes', 'icon' => 'circle-o' , 'url' => ['/sisai/relatorios/avaliacaodocente'],
                            'visible' => $sisaiAdmin || Yii::$app->user->can('Docente'),
                        ],
                        [
                            'label' => 'Discente pelos docentes', 'icon' => 'circle-o', 'url' => ['/sisai/relatorios/avaliacaodiscente'],
                            'visible' => $sisaiAdmin,
                        ],
                        [
                            'label' => 'Autoavaliação Discente', 'icon' => 'circle-o', 'url' => ['/sisai/relatorios/autoavaliacaodiscente'],
                            'visible' => $sisaiAdmin,
                        ],
                        [
                            'label' => 'Autoavaliação Docente', 'icon' => 'circle-o', 'url' => ['/sisai/relatorios/autoavaliacaodocente'],
                            'visible' => $sisaiAdmin,
                        ],
                        [
                            'label' => 'Ambiente Virtual (AVA)', 'icon' => 'circle-o', 'url' => ['/sisai/relatorios/avaliacaoambientevirtual'],
                            'visible' => $sisaiAdmin,
                        ],
                        [
                            'label' => 'Infraestrutura', 'icon' => 'circle-o', 'url' => ['/sisai/relatorios/avaliacaoinfraestrutura'],
                            'visible' => $sisaiAdmin,
                        ],
                        [
                            'label' => 'Acessibilidade', 'icon' => 'circle-o', 'url' => ['/sisai/relatorios/avaliacaoacessibilidade'],
                            'visible' => $sisaiAdmin,
                        ],
                        [
                            'label' => 'Gestão Acadêmica', 'icon' => 'circle-o', 'url' => ['/sisai/relatorios/avaliacaogestaoacademica'],
                            'visible' => $sisaiAdmin,
                        ],
                        [
                            'label' => 'Qualidade de Vida', 'icon' => 'circle-o', 'url' => ['/sisai/relatorios/avaliacaoqualidadevida'],
                            'visible' => $sisaiAdmin,
                        ],
                        [
                            'label' => 'Condições de Trabalho', 'icon' => 'circle-o', 'url' => ['/sisai/relatorios/avaliacaocondicoestrabalho'],
                            'visible' => $sisaiAdmin,
                        ],
                        [
                            'label' => 'Técnicos', 'icon' => 'circle-o', 'url' => ['/sisai/relatorios/avaliacaotecnicos'],
                            'visible' => $sisaiAdmin,
                        ],

                    ],'visible' => $sisai,
                ],
                [
                    'label' => 'Periodo de Avaliação', 'icon' => 'calendar' , 'url' => ['/sisai/sisaiperiodoavaliacao'],
                        'visible' => $sisaiAdmin,
                ],
                [
                    'label' => 'Questionários', 'icon' => 'list-alt' , 'url' => ['/sisai/sisaiquestionario'],
                        'visible' => $siscatAdmin,
                ],
                [
                    'label' => 'SisaiAvaliacao', 'icon' => 'list-alt' , 'url' => ['/sisai/sisaiavaliacao'],
                        'visible' => $siscatAdmin,
                ],
                [
                    'label' => 'Alunos', 'icon' => 'users' , 'url' => ['/sisai/sisaialuno'],
                        'visible' => $sisaiAluno,
                ],
                [
                    'label' => 'Atualizar BD', 'icon' => 'refresh' , 'url' => ['/sisai/default/atualizartabelaaluno'],
                        'visible' => $siscatAdmin,
                ],
                [
                    'label' => 'Colegiados para ATUV', 'icon' => 'refresh' , 'url' => ['/sisai/sisaicolegiadosemestreatuv'],
                        'visible' => $siscatAdmin,
                ],
                [
                    'label' => 'Lista ATUV', 'icon' => 'file-excel-o', 'url' => ['/sisai/default/listaatuv'],
                        'visible' => $sisaiAluno,
                ],
            ]
        ];

    }
}
