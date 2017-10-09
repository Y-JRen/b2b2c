<?php

namespace frontend\modules\web\controllers;

use frontend\controllers\BaseController;

/**
 * Class DetailController
 * 详情页面控制器信息
 * @package frontend\modules\web\controllers
 */
class DetailController extends BaseController
{
    /**
     * 详情信息
     *
     * @return mixed|string
     */
    public function actionIndex()
    {
        return $this->returnJson();
    }

    /**
     * 车型详情信息
     *
     * @return mixed|string
     */
    public function actionDetail()
    {
        return $this->returnJson();
    }

    /**
     * 车型配置信息
     *
     * @return mixed|string
     */
    public function actionConfig()
    {
        return $this->returnJson();
    }

    /**
     * 车型视频信息
     *
     * @return mixed|string
     */
    public function actionVideo()
    {
        return $this->returnJson();
    }
}