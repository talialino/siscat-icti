<?php

use yii\helpers\Html;
use app\assets\SisrhAsset;

SisrhAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\sisrh\models\SisrhSetorPessoa */
?>
<div class="sisrh-setor-pessoa-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
