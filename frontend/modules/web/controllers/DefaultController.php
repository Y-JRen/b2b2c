<?php

namespace frontend\modules\web\controllers;

use common\helpers\Helper;
use common\models\PartnerApplyJoinLog;
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
        $logs = new PartnerApplyJoinLog();
        $logs->load($this->privateParam, '');
        if ($logs->save()) {
            $this->handleJson($this->privateParam);
        } else {
            $this->arrJson['errCode'] = 4001;
            $this->arrJson['errMsg'] = Helper::arrayToString($logs->getErrors());
        }

        return $this->returnJson();
    }
}