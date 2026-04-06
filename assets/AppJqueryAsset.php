<?php
namespace app\assets;

use yii\web\AssetBundle;

class AppJqueryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/jquery-3.5.0.min.js',
    ];
}