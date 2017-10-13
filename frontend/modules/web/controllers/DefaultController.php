<?php

namespace frontend\modules\web\controllers;

use frontend\controllers\BaseController;

/**
 * PC端首页基本控制器
 *
 * Class DefaultController
 *
 * @package frontend\modules\web\controllers
 */
class DefaultController extends BaseController
{
    /**
     * 首页轮播图信息
     *
     * @return mixed|string
     */
    public function actionIndex()
    {
        return $this->returnJson();
    }

    /**
     * 首页banner 位信息
     *
     * @return mixed|string
     */
    public function actionBanner()
    {
        return $this->returnJson();
    }

    /**
     * 首页申请加入合作商信息
     *
     * @return mixed|string
     */
    public function actionJoin()
    {
        return $this->returnJson();
    }
}