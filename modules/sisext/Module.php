<?php

namespace app\modules\sisext;
use Yii;
use yii\web\HttpException;

/**
 * sisext module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\sisext\controllers';

    public $layout = 'main';

    /**
     * Indica se o módulo está ativo. Este parâmetro é definido no arquivo de configuração
     * e tem o propósito de auxiliar nas manutenções dos módulos
     */
    public $ativo;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if(!$this->ativo && !Yii::$app->user->can('siscatAdministrar'))
            throw new HttpException(503, 'O sistema está em manutenção. Tente novamente mais tarde.');

        // custom initialization code goes here
    }
}
