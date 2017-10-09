<?php

namespace frontend\modules\web;

use yii\base\Module;

/**
 * web module definition class
 */
class web extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\web\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
