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
class VueAsset extends AssetBundle
{
    public $baseUrl = '/asset/dist/';
    public function init(){
        $this->sourcePath = __DIR__ . '/dist/';
        parent::init();
    }
    
    /**
     * @var array
     */
    public $css = [
        'css/index.css'
    ];
    
    /**
     * @var array
     */
    public $js = [
        'js/vue.js',
        'js/index.js',
    ];
    
    public $depends = [
    
    ];
}