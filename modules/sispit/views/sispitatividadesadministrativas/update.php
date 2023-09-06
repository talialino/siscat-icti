<?php

/* @var $this yii\web\View */
/* @var $model app\modules\sispit\models\SispitAtividadesAdministrativas */
use app\assets\SispitAsset;
SispitAsset::register($this);
?>
<div class="sispit-atividades-administrativas-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
