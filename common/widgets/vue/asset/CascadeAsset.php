<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/19
 * Time: 17:18
 */

namespace common\widgets\vue\asset;


use yii\web\AssetBundle;

/**
 * vue 插件相关 js 和 css
 *
 * Class CascadeAsset
 * @package common\widgets\cascade
 */
class CascadeAsset extends AssetBundle
{
    public function init(){
        $this->sourcePath = __DIR__ . '/';
        parent::init();
    }
    
    /**
     * @var array
     */
    public $css = [
        'https://unpkg.com/element-ui/lib/theme-default/index.css'
    ];
    
    /**
     * @var array
     */
    public $js = [
        'https://unpkg.com/vue/dist/vue.js',
        'https://unpkg.com/element-ui/lib/index.js',
        'js/cascade.js'
    ];
    
    public $depends = [
    
    ];
}