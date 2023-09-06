<?php

namespace app\modules\siscoae;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
/**
 * siscoae module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\siscoae\controllers';

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

        $siscoae = Yii::$app->user->can('siscoae');

        if(!$siscoae) 
            throw new NotFoundHttpException(Yii::t('app',"You don't have permission to view this page."));

        //redireciona a página de erro para exibir o layout do módulo
        Yii::$app->errorHandler->errorAction = 'siscoae/default/error';

       // $siscoaeAdministrar = Yii::$app->user->can('siscoaeAdministrar');

        $this->menu = [
            'items' => [
                ['label' => 'Formulario Socioeconômico', 'icon' => 'list-alt', 'url' => ['/siscoae/formulariosocioeconomico'], 'visible' => $siscoae],
            ]
        ];
    }
}
