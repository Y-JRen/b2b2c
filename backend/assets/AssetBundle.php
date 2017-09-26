<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/26
 * Time: 11:11
 */

namespace backend\assets;


class AssetBundle extends \yii\web\AssetBundle
{
    /**
     * @inherit
     */
    public $baseUrl = '@web/adminlte/bootstrap';
    
    /**
     * @inherit
     */
    public $css = [
        'css/font-awesome.min.css',
    ];
    
    /**
     * Initializes the bundle.
     * Set publish options to copy only necessary files (in this case css and font folders)
     * @codeCoverageIgnore
     */
    public function init()
    {
        parent::init();
        
        $this->publishOptions['beforeCopy'] = function ($from, $to) {
            return preg_match('%(/|\\\\)(fonts|css)%', $from);
        };
    }
}