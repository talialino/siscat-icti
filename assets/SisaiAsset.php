<?php

namespace app\assets;

use yii\web\AssetBundle;

class SisaiAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/sisai.css?v=3',
    ];
    public $js = [
        'js/modal.js?v=1',
        'js/tooltip.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
