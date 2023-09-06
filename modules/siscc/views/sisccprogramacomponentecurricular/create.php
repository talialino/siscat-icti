<?php

use yii\helpers\Html;
use app\assets\SisccAsset;
SisccAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricular */

$this->title = 'Criar Programa de Componente Curricular';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Programas de Componentes Curriculares', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-programa-componente-curricular-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
