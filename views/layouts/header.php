<?php

use app\modules\sisai\models\SisaiAluno;
use app\modules\sisrh\models\SisrhPessoa;
use yii\helpers\Html;

$user = Yii::$app->user->identity;

if(!(Yii::$app->session->has('siscat_pessoa') || Yii::$app->session->has('siscat_aluno')) && isset($user)){
    //Coloca o nome do usuário na sessão caso isso não tenha sido feito pelo login (ocorrerá quando administrador logar como outro usuário)
    $pessoa = SisrhPessoa::usuarioAtivo($user->id);
    if(isset($pessoa)){
        Yii::$app->session->set('siscat_pessoa', $pessoa);
    }
    else{
        $aluno = SisaiAluno::usuarioAtivo($user->id);
        if(isset($aluno)){
            Yii::$app->session->set('siscat_aluno', $aluno);
        }
    }
}
$nomeUsuario = isset($user) ? Yii::$app->session->get(Yii::$app->session->has('siscat_pessoa') ? 'siscat_pessoa' : 'siscat_aluno')->nome : '';
/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><i class="fa fa-home text-aqua"></i></span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <?php if(!Yii::$app->user->isGuest):?>
        <div class="navbar-custom-menu">
            
            <ul class="nav navbar-nav">
                
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-user-circle-o text-white" style="font-size: 20px;"></i>
                        <span class="hidden-xs"><?php if(isset($user))echo $user->username?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">

                            <p>
                            <?=$nomeUsuario?>
                                <small><?php if(Yii::$app->session->has('last_login')) echo Yii::t('app','Last login at').' '.date('d/m/Y H::i', Yii::$app->session->get('last_login'));?></small>
                            
                            </p>

                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Yii::$app->session->has('siscat_pessoa') ? Html::a(
                                    Yii::t('app','Personal Date'),
                                    ['/sisrh/sisrhpessoa/person'],
                                    ['class' => 'btn btn-default btn-flat']
                                ) : '' ?>
                                <?php if(Yii::$app->session->has('superuser')) echo Html::a(
                                        '<span class="fa fa-undo"></span>', ['/user/admin/switch', 'id' => Yii::$app->session->get('superuser')], [
                                        'title' => 'Usuário original',
                                        'class' => 'btn btn-default btn-flat',
                                        'data-method' => 'POST',
                                        ]);
                                 ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    Yii::t('user','Sign out'),
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
            </ul>
        </div>
        <?php endif; ?>
    </nav>
</header>
