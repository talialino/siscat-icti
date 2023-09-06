<?php

namespace app\modules\siscc;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
/**
 * siscc module definition class
 */
class Module extends \yii\base\Module 
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\siscc\controllers';

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

        if(!Yii::$app->user->can('siscc'))
            throw new NotFoundHttpException(Yii::t('app',"You don't have permission to view this page."));
        
        //redireciona a página de erro para exibir o layout do módulo
        Yii::$app->errorHandler->errorAction = 'siscc/default/error';
        
        // custom initialization code goes here
        $sisccDocente = Yii::$app->user->can('sisccDocente');
        $sisccAdministrar = Yii::$app->user->can('sisccAdministrar');
        $sisccAdministrarVinculo = Yii::$app->user->can('sisccAdministrarVinculo');
        
        $this->menu = [
            'items' => [
                ['label' => 'Meus Programas', 'icon' => 'list-alt', 'url' => ['/siscc/sisccprogramacomponentecurricularpessoa/'], 'visible' => $sisccDocente],
                ['label' => 'Componente Curricular', 'icon' => 'list-alt', 'url' => ['/siscc/siscccomponentecurricular'], 'visible' => $sisccAdministrarVinculo],
                ['label' => 'Gerenciar Programas', 'icon' => 'gear', 'url' => ['/siscc/sisccprogramacomponentecurricular'], 'visible' => $sisccAdministrarVinculo],
                ['label' => 'Gerenciar Colegiado', 'icon' => 'gear', 'url' => ['/siscc/gerenciarcolegiado'], 'visible' => Yii::$app->user->can('sisccGerenciarColegiado')],
                ['label' => 'Coordenação Acadêmica', 'icon' => 'graduation-cap', 'visible' => $sisccAdministrar,
                    'items' => [
                        ['label' => 'Gerenciamento', 'icon' => 'circle-o', 'url' => ['/siscc/gerenciamentocac'],],
                        ['label' => 'Resumo', 'icon' => 'circle-o', 'url' => ['/siscc/gerenciamentocac/resumo'],]
                ],],
                ['label' => 'Semestre', 'icon' => 'calendar', 'url' => ['/siscc/sisccsemestre'], 'visible' => $sisccAdministrar],
                ['label' => 'Parecer', 'icon' => 'check', 'url' => ['/siscc/sisccparecer'], 'visible' => $sisccDocente],
                ['label' => 'Referências Bibliográficas', 'icon' => 'book', 'url' => ['/siscc/sisccprogramacomponentecurricularbibliografia'], 'visible' => Yii::$app->user->can('sisccAdministrarBibliografia')],
                ['label' => 'Visualizar Programas', 'icon' => 'eye', 'url' => ['/siscc/sisccprogramacomponentecurricular/visualizarprogramas'], 'visible' => Yii::$app->user->can('siscc')],
            ]
        ];
    }
}
