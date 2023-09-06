<?php

function base64url_encode($data) { 
    return strtr(base64_encode($data), '+/=', '-_~');
  }

/* @var $this yii\web\View */

use app\modules\sisai\models\SisaiAluno;
use app\modules\sisrh\models\SisrhPessoa;
use yii\helpers\Html;

$this->title =Yii::$app->name;

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

?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?=Yii::t('app','Welcome to')?> SISCAT!</h1>

        <p class="lead"><?=Yii::t('app',
            "Here you have access to all CAT's systems. Just select the one you want in the menu below."
        )?></p>
    </div>
    <div class="body-content">

        <div class="row">
            <?php if (Yii::$app->user->can('sisape')):?>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="card-heading">
                            <div class="card-image">
                                <a href="sisape"><?= Html::img('@web/images/sisape.png', ['alt'=>'Sisape', 'class'=>'']);?></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><a href="sisape">SISAPE</a></h5>
                            <p class="card-text">Sistema de projetos de pesquisa e extensão</p>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <?php if (Yii::$app->user->can('siscc')):?>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="card-heading">
                            <div class="card-image">
                                <a href="siscc"><?= Html::img('@web/images/siscc.png', ['alt'=>'SISCC', 'class'=>'']);?></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><a href="siscc">SISCC</a></h5>
                            <p class="card-text">Sistema de componentes curriculares</p>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <?php if (Yii::$app->user->can('sisrh')):?>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="card-heading">
                            <div class="card-image">
                            <a href="sisrh"><?= Html::img('@web/images/sisrh.png', ['alt'=>'SISRH', 'class'=>'']);?></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><a href="sisrh">SISRH</a></h5>
                            <p class="card-text">Sistema de recursos humanos</p>
                        </div>
                    </div>                
                </div>
            <?php endif;?>
            <?php if (Yii::$app->user->can('sispit')):?>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="card-heading">
                            <div class="card-image">
                                <a href="sispit"><?= Html::img('@web/images/sispit.png', ['alt'=>'SISPIT', 'class'=>'']);?></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><a href="sispit">SISPIT</a></h5>
                            <p class="card-text">Sistema de PIT e RIT</p>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <?php if (Yii::$app->user->can('reservasala')):?>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="card-heading">
                            <div class="card-image">
                                <form id="reservaSalaForm" action="https://sistemasims.ufba.br/reservasala/index.php/login/siscat/" method="POST">
                                    <input type="hidden" name="id" value="<?php echo base64url_encode($user->id.'-'.$user->username.'-'.$nomeUsuario.'-'.Yii::$app->user->can('reservasalaAdministrar')); ?>" />
                                </form>
                                <a style='cursor: pointer' onclick="$('#reservaSalaForm').submit()"><?= Html::img('@web/images/reserva-sala.png', ['alt'=>'Reserva de Sala', 'class'=>'']);?></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><a style='cursor: pointer' onclick="$('#reservaSalaForm').submit()"><?=Yii::t('app','Room\'s Booking')?></a></h5>
                            <p class="card-text">Sistema de reserva de sala</p>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <?php if (Yii::$app->user->can('sisai')):?>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="card-heading">
                            <div class="card-image">
                                <a href="sisai"><?= Html::img('@web/images/sisai.png', ['alt'=>'SISCC', 'class'=>'']);?></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><a href="sisai">SISAI</a></h5>
                            <p class="card-text">Sistema de avaliação institucional</p>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <?php if (Yii::$app->user->can('sisliga')):?>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="card-heading">
                            <div class="card-image">
                                <a href="sisliga"><?= Html::img('@web/images/sisliga1.png', ['alt'=>'Sisliga', 'class'=>'']);?></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><a href="sisliga">SISLIGA</a></h5>
                            <p class="card-text">Sistema de ligas acadêmicas</p>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <?php if (Yii::$app->user->can('siscoae')):?>
                <div class="col-lg-4 col-sm-6">
                    <div class="card">
                        <div class="card-heading">
                            <div class="card-image">
                                <a href="siscoae"><?= Html::img('@web/images/siscoae.png', ['alt'=>'SISCC', 'class'=>'']);?></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><a href="siscoae">SISCOAE</a></h5>
                            <p class="card-text">Sistema de Suporte à Coordenação de Ações Afirmativas e Assistência Estudantil</p>
                        </div>
                    </div>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>
