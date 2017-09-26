<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\base\Exception;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    
    public $baseUrl = '@web/adminlte';
    
    public $css = [
        'dist/css/AdminLTE.min.css',
    ];
    
    public $skin = '_all-skins';
    
    public $js = [
        'dist/js/app.js',
        'plugins/layer/layer.js'
    ];
    
    public $depends = [
        'backend\assets\AssetBundle',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        // Append skin color file if specified
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }
            
            $this->css[] = sprintf('dist/css/skins/%s.min.css', $this->skin);
        }
        
        parent::init();
    }
}
