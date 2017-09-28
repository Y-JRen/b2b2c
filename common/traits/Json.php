<?php

namespace common\traits;

use Yii;
use \yii\web\Response;

/**
 * Trait Json 处理json 返回
 * @author liujx
 * @package common\traits
 */
trait Json
{
    /**
     * 定义返回json的数据
     * @var array
     */
    protected $arrJson = [
        'errCode' => 201,
        'errMsg'  => '请求参数为空',
        'data'    => [],
    ];

    /**
     * 响应ajax 返回
     * @param string $array    其他返回参数(默认null)
     * @return mixed|string
     */
    protected function returnJson($array = null)
    {
        // 判断是否覆盖之前的值
        if ($array) $this->arrJson = array_merge($this->arrJson, $array);

        // 没有错误信息使用code 确定错误信息
        if (empty($this->arrJson['errMsg'])) {
            $this->arrJson['errMsg'] = '请求数据错误';
        }

        // 设置JSON返回
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->arrJson;
    }

    /**
     * handleJson() 处理返回数据
     * @param mixed $data     返回数据
     * @param integer   $errCode  返回状态码
     * @param null  $errMsg   提示信息
     */
    protected function handleJson($data, $errCode = 0, $errMsg = null)
    {
        $this->arrJson['errCode'] = $errCode;
        $this->arrJson['data']    = $data;
        if ($errMsg !== null) {
            $this->arrJson['errMsg'] = $errMsg;
        }
    }
}