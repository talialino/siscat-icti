<?php

use yii\helpers\Html;
use app\assets\SisccAsset;
SisccAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularPessoa */

$this->title = 'Adicionar Docente';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Siscc Programa Componente Curricular Pessoas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-programa-componente-curricular-pessoa-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
