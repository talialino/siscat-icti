<?php

namespace app\controllers;

use app\models\AlunoForm;
use app\models\CGDPContactForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ErrorContactForm;
use  dektrium\user\models\User;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) //se não estiver logado, redireciona para tela de login
            $this->redirect(['user/login']);//redireciona para tela de login da extensão dektrium/user
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::createObject(LoginForm::class);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $this->redirect(['user/login']);//redireciona para tela de login da extensão dektrium/user
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContactcgdp()
    {
        $model = new CGDPContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contactcgdp', [
            'model' => $model,
        ]);
    }

     /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact($error)
    {
        
        $model = new ErrorContactForm();
        $model->mensagemErro = $error;

        if(!Yii::$app->user->isGuest){
            $model->loginUfba = Yii::$app->user->identity->username;
            $model->name = Yii::$app->session->get(Yii::$app->session->has('siscat_pessoa') ? 'siscat_pessoa' : 'siscat_aluno')->nome;
        }

        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionAluno()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::createObject(AlunoForm::class);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }
        return $this->render('aluno', [
            'model' => $model,
        ]);
    }
}
