<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\siscc\models\SisccProgramaComponenteCurricularBibliografia */

$this->title = 'Create Siscc Programa Componente Curricular Bibliografia';
$this->params['breadcrumbs'][] = ['label' => strtoupper('siscc'), 'url' => ['/'.strtolower('siscc')]];
$this->params['breadcrumbs'][] = ['label' => 'Siscc Programa Componente Curricular Bibliografias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siscc-programa-componente-curricular-bibliografia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
