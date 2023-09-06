<?php
use \yii\web\Request;
use \kartik\datecontrol\Module;

$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'siscat',
    'name' => 'SISCAT',
    'language' => 'pt-BR',
    'timezone' => 'America/Bahia',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'formatter' => [
            'dateFormat' => 'short',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'U_i7KE-hTGdwKYfIbZUletaIKShgaSx1',
            'baseUrl' => $baseUrl, 
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => YII_ENV_DEV,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,

            'baseUrl' => $baseUrl,
            'rules' => [

               '<controller>s' => '<controller>/index',
              // '<controller>/<id:\d+>/<action>' => '<controller>/<action>',
               '<controller>/<id:\d+>' => '<controller>/view',

            ],
        ],
        'authManager' => [
            'class' => 'dektrium\rbac\components\DbManager',
            'defaultRoles' => ['Biblioteca','CGDP','CAC','Colegiado','NucleoAcademico', 'NUPAD', 'SecretariaExecutiva','NUSEC', 'COPEX', 'ComissaoPitRit', 'ComissaoLiga'],
        ],
        
    ],
    'params' => $params,
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 21600,
            'enableRegistration' => false,
            'enableConfirmation' => false,
            'enableAccountDelete' => false,
            'enablePasswordRecovery' => false,
            'cost' => 12,
            'modelMap' => [
                'LoginForm' => 'app\models\LoginForm',
            ],
        ],
        'rbac' => 'dektrium\rbac\RbacWebModule',
        'sisape' => [
            'class' => 'app\modules\sisape\Module',
            'skin' => 'skin-green',
            'ativo' => true,
        ],
        'siscc' => [
            'class' => 'app\modules\siscc\Module',
            'skin' => 'skin-purple',
            'ativo' => true,
        ],
        'sisrh' => [
            'class' => 'app\modules\sisrh\Module',
            'skin' => 'skin-red',
            'ativo' => true,
        ],
        'sispit' => [
            'class' => 'app\modules\sispit\Module',
            'skin' => 'skin-yellow',
            'ativo' => true,
        ],
        'sisai' => [
            'class' => 'app\modules\sisai\Module',
            'skin' => 'skin-black',
            'ativo' => true,
        ],
        'sisext' => [
            'class' => 'app\modules\sisext\Module',
            'ativo' => true,
        ],
        'sisliga' => [
            'class' => 'app\modules\sisliga\Module',
            'ativo' => true,
        ],
        'siscoae' => [
            'class' => 'app\modules\siscoae\Module',
            'ativo' => true,
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module',
            'autoWidget' => true,
            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                Module::FORMAT_DATE => ['type'=>2, 'pluginOptions'=>['autoclose'=>true, 'clearBtn' => false]], // example
                Module::FORMAT_DATETIME => ['pluginOptions'=>['autoclose'=>true]], // setup if needed
                Module::FORMAT_TIME => [], // setup if needed
            ],
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['200.128.56.103','200.128.56.104', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['200.128.56.103','200.128.56.104', '::1'],
        'generators' => [
            'crudGii' => [
                'class' => 'app\templatesGii\crud\Generator',
                'templates' => [
                    'my' => '@app/templatesGii/crud/default',
                ]
                ],
        ],
    ];
}

return $config;
