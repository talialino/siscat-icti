<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="content-wrapper">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                   // echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo strtoupper(\yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    ));
                } ?>
            </h1>
        <?php } ?> 

        <?=
        Breadcrumbs::widget(
            [
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
        <b><?=Yii::t('app','Version')?></b> 2.0
    </div>
    Núcleo de Tecnologia da Informação - (77) 3429 2705 | &copy; <?= date("Y")?> - UFBA/CAT/IMS.
</footer>