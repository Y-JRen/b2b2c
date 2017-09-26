<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/19
 * Time: 14:08
 */

namespace common\client\api;

/**
 * 高德地图API
 *
 * Class AMapClient
 * @package common\client
 */
class AMapClient extends BaseClient
{
    /**
     * 地理编码 API 服务地址
     */
    const GET_CODE_GEO_URL = 'http://restapi.amap.com/v3/geocode/geo';
    
    /**
     * 高德Key
     *
     * @var string
     */
    protected $key = '';
    
    /**
     * 批量控制
     *
     * @var bool
     */
    protected $batch = false;
    
    /**
     * 返回数据格式类型,可选输入内容包括：JSON，XML
     *
     * @var string
     */
    protected $output = "JSON";
    
    /**
     * 数字签名
     *
     * @var string
     */
    protected $sig;
    
    /**
     * 回调函数
     *
     * @var
     */
    protected $callback;
    
    /**
     * 初始化信息
     *
     * AMapClient constructor.
     */
    public function __construct()
    {
        $this->key = \Yii::$app->params['amap']['key'];
    }
    
    
    /**
     * 根据地址信息，获取地理编码
     *
     * @param  string $address 结构化地址信息 如：北京市朝阳区阜通东大街6号
     * @param string $city
     *
     * @return boolean | array
     */
    public function getCodeGeo($address, $city = '')
    {
        if(!$address) {
            $this->error = '地址信息必填';
            return false;
        }
        $param = [
            'key' => $this->key,
            'address' => $address,
            'city' => $city,
            'batch' => $this->batch,
            'sig' => $this->sig,
            'output' => $this->output,
        ];
        $this->url = self::GET_CODE_GEO_URL;
        $data = $this->httpGet($param);
        return $data;
    }
}