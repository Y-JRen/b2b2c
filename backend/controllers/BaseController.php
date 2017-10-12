<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/10/11
 * Time: 10:53
 */

namespace backend\controllers;

use yii\web\Controller;

class BaseController extends Controller
{
    /**
     * ajax 返回格式
     *
     * @param array|string $data
     * @param int $isSuccess
     */
    public function returnJson($data, $isSuccess = 1)
    {
        
        die(json_encode([
            'data' => $data,
            'isSuccess' => $isSuccess
        ]));
    }
}