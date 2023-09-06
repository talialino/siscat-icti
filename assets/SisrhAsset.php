<?php

namespace app\assets;

use yii\web\AssetBundle;

class SisrhAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/sisrh.css?v=2',
    ];
    public $js = [
        'js/modal.js?v=1',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
