<?php

namespace frontend\controllers;

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
                header('application/json; charset=utf-8');
                exit($this->returnJson([
                    'errCode' => 1002,
                ]));
            }
        }

        return $isReturn;
    }
}