<?php

use yii\helpers\Html;
use app\assets\SispitAsset;
SispitAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitMonitoria */
?>
<div class="sispit-monitoria-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
