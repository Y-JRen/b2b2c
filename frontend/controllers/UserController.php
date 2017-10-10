<?php

namespace frontend\controllers;

use yii\helpers\Json;

/**
 * Class UserController
 * 必须要用户登录的控制器，所有方法都需要检查用户是否已经登陆
 *
 * @package frontend\controllers
 */
class UserController extends BaseController
{
    public function beforeAction($action)
    {
        $isReturn = parent::beforeAction($action);
        if ($isReturn) {
            // 没有登陆
            if (!$this->checkLogin()) {
                header('Content-Type: application/json; charset=UTF-8');
                exit(Json::encode($this->returnJson([
                    'errCode' => 1002,
                ])));
            }
        }

        return $isReturn;
    }
}