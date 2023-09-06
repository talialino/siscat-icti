<?php
use yii\helpers\Html;
use app\assets\SisextAsset;

SisextAsset::register($this);

switch (Yii::$app->controller->moduloId) {
    case "sisape":
        $subTitulo = "SISAPE - Sistema de Acompanhamento de Projetos de Pesquisa e Extensão";
        break;
    case "siscc":
        $subTitulo = "SISCC - Sistema de Componentes Curriculares";
        break;
    case "sisrh":
        $subTitulo = "SISRH - Sistema de Recursos Humanos";
        break;
    case "sispit":
        $subTitulo = "SISPIT - Sistema de PIT/RIT";
        break;
    case "sisai":
        $subTitulo = "SISAI - Sistema de Avaliação Institucional";
        break;
}

?>

<header class="main-header">
    <div class="siscatTopo">
        <div class="pull-left" style="border-right: 1px solid #7fa9c1;padding-right: 5px;"><img src="<?= "/siscat/images/icon-siscat.png"?>" style="width:52px;height:52px"> </div>
        <div class="pull-left"><img src="<?= "/siscat/images/".Yii::$app->controller->moduloId.".png"?>" style="width:52px;height:52px"></div>
        <h2 class="cabecalho1"><?=$subTitulo?></h2>
    </div>
</header>
