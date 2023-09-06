<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use app\assets\SisccAsset;
SisccAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricular */

$this->title = 'Programa de Componente Curricular';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Meus Programas', 'url' => ['/siscc/sisccprogramacomponentecurricularpessoa']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-programa-componente-curricular-view">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_view', ['model' => $model]); ?>
</div>
