<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/19
 * Time: 13:41
 */

namespace common\client\api;


use common\logic\Instance;
use yii\httpclient\Client;

/**
 * HTTP 客户端基础类
 *
 * Class BaseClient
 * @package common\client
 */
class BaseClient extends Instance
{
    /**
     * 错误信息
     *
     * @var string
     */
    public $error = '';
    
    /**
     * 请求URL
     *
     * @var
     */
    public $url;
    
    /**
     * GET 请求
     *
     * @param $data
     *
     * @return bool
     */
    public function httpGet($data)
    {
        return $this->http($data, "get");
    }
    
    /**
     * POST 请求
     *
     * @param $data
     *
     * @return bool
     */
    public function HttpPost($data)
    {
        return $this->http($data, "post");
    }
    
    /**
     * 发送HTTP请求
     *
     * @param $data
     * @param $method
     *
     * @return bool
     */
    private function http($data, $method)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod($method)
            ->setUrl($this->url)
            ->setData($data)
            ->send();
        if ($response->isOk) {
           return $response->data;
        }
        return false;
    }
    
}