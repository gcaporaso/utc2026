<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    //public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $css = [
        'mappe/css/leaflet.css',
        'mappe/css/L.Control.Opacity.css',
        'mappe/css/leaflet.extra-markers.min.css',
        'mappe/css/leaflet-measure.css',
        'mappe/css/qgis2web.css',
        'mappe/css/leaflet.css',
        'mappe/css/leaflet.contextmenu.min.css',
        'mappe/css/L.Control.Layers.Tree.css',
        'mappe/css/catastostyle.css',
        //'mappe/css/tree_control.css',
        'mappe/css/vstyle.css'
        
        ];
    public $js = [
        'js/jquery-3.5.0.min.js',
        'mappe/js/leaflet.js',
        'mappe/js/stili.js',
        'mappe/js/proj4.js',
        'mappe/js/proj4leaflet.js',
        'mappe/js/sistemi_riferimento.js',
        'mappe/js/layerstyle.js',
        'mappe/js/googlemap.js',
        //'mappe/js/mappa.js',
        'mappe/js/leaflet.edgebuffer.js', 
        'mappe/js/L.Control.Opacity.js',
        'mappe/js/layers.js',
        'mappe/js/catastali_json.js',
        'mappe/js/leaflet.extra-markers.min.js',
        'mappe/js/leaflet-measure.js?v=2',
        'mappe/js/leaflet.contextmenu.min.js',
        'mappe/js/getfutureinfo.js',
        'mappe/js/leaflet-pip.js',
        'mappe/js/catiline.js',
        'mappe/js/leaflet.shpfile.js',
        'mappe/js/shp.js',
        'mappe/js/L.Control.Layers.Tree.js',
        'mappe/js/leaflet.pattern.js',
        'mappe/js/multi-style-layer.js',
        'mappe/js/wms.map.catasto.js',
        'mappe/js/function_collection.js?v=3',
        'mappe/js/MyTileLayer.WMS.js',
        'plugins/bs-custom-file-input/bs-custom-file-input.min.js',
        
        //'mappe/js/catastali_vettoriali.js',
        
        'mappe/js/cerca_particelle.js',
        'mappe/js/popup.js',
        'mappe/js/controlli.js',
        'mappe/js/pratiche_edilizie.js?v=5',
        'mappe/js/scheda_urbanistica.js',
        
        ];
    public $depends = [
         
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
