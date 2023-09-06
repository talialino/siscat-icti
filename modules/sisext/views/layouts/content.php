<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="content-wrapper">
<section class="content-header">
        <?=
        Breadcrumbs::widget(
            [
                'homeLink' => false,
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>
    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <strong><?=Yii::t('app','Version')?></strong> 2.0
    </div>
    <img src="<? echo "/siscat/images/brasao_ufba.jpg"?>" style="width: 32px;height: 50px; margin-right:20px">
    Núcleo de Tecnologia da Informação - (77) 3429 2705 | &copy; <?= date("Y");?> - UFBA/CAT/IMS.
</footer>