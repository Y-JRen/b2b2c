<?php

namespace common\logic;

use common\models\HttpLog;
use yii\helpers\Json;

/**
 * Class HttpLogic 项目中的http请求基础功能类，所有curl请求必须走此处
 * @package common\logic
 */
class HttpLogic extends Instance
{
    /**
     * @param string $url 请求地址
     * @param array $options 请求配置信息
     * @param bool $isToJson 是否转json 默认转换
     * @param bool $writeLog 是否写日志 默认 false
     * @return mixed
     */
    public static function curl_get_contents($url, $options = [], $isToJson = true, $writeLog = false)
    {
        // 默认配置信息
        $default = [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; rv:17.0) Gecko/17.0 Firefox/17.0",
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 3,
        ];

        // 其他配置信息
        foreach ($options as $key => $value) {
            $default[$key] = $value;
        }

        $ch = curl_init();
        curl_setopt_array($ch, $default);
        $result = curl_exec($ch);

        // curl 发送失败记录日志
        if ($result === false) {
            $errCode = curl_errno($ch);
            // 记录错误信息
            $errMsg = 'errCode:' . $errCode . '; errMsg:' . curl_strerror($errCode);
        } else {
            $errMsg = '';
        }

        // 关闭连接
        curl_close($ch);

        // 存在错误或者指定需要写入日志
        if ($writeLog || $errMsg) {
            $objHttpLogMod = new HttpLog();
            if (isset($options[CURLOPT_POSTFIELDS])) {
                $objHttpLogMod->input_data = $options[CURLOPT_POSTFIELDS];
            }
            $objHttpLogMod->url = $url;
            $objHttpLogMod->result = (string)$result;
            $objHttpLogMod->error = $errMsg;
            $objHttpLogMod->save();

        }

        // 转换json 信息
        if ($result && $isToJson) $result = Json::decode($result);
        return $result;
    }

    /**
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @param array $options 请求配置信息
     * @param bool $isToJson 是否将结果转json
     * @param bool $writeLog 是否写日志 默认 false
     * @return mixed
     */
    public static function http_get($url, $params = [], $options = [], $isToJson = true, $writeLog = false)
    {
        $strParams = $params ? http_build_query($params) : '';
        $strParams = strpos($url, '?') ? $strParams : '?' . $strParams;
        return self::curl_get_contents($url . $strParams, $options, $isToJson, $writeLog);
    }

    /**
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @param array $options 请求配置信息
     * @param bool $isToJson 是否将结果转json
     * @param bool $writeLog 是否写日志 默认 false
     * @return mixed
     */
    public static function http_post($url, $params = [], $options = [], $isToJson = true, $writeLog = false)
    {
        $options[CURLOPT_POST] = 1;
        $options[CURLOPT_POSTFIELDS] = http_build_query($params);
        return self::curl_get_contents($url, $options, $isToJson, $writeLog);
    }
}