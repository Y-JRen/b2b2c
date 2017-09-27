<?php
/**
 * 项目中的http请求基础功能类，所有curl请求必须走此处
 * User: 雕
 * Date: 2017/9/13
 * Time: 14:28
 */
namespace common\logic;

use common\models\HttpLog;

class HttpLogic extends Instance
{
    /**
     * @param $url
     * @param array $options
     * @param bool $writeLog
     * @return mixed
     */
    public static function curl_get_contents($url, $options = array(), $writeLog = false)
    {
        $default = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; rv:17.0) Gecko/17.0 Firefox/17.0",
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 3,
        );
        foreach ($options as $key => $value) {
            $default[$key] = $value;
        }
        $ch = curl_init();
        curl_setopt_array($ch, $default);
        $result = curl_exec($ch);
        curl_close($ch);

        if ($writeLog) {//写日志
            $objHttpLogMod = new HttpLog();
            if (isset($options[CURLOPT_POSTFIELDS])) {
                $objHttpLogMod->inputData = $options[CURLOPT_POSTFIELDS];
            }
            $objHttpLogMod->url             = $url;
            $objHttpLogMod->result          = $result;
            $objHttpLogMod->create_time    = date('Y-m-d H:i:s');
            $objHttpLogMod->save();
        }
        return $result;
    }

    /**
     * @param $url
     * @param array $params
     * @param array $options
     * @param bool $writeLog
     * @return mixed
     */
    public static function http_get($url, $params = array(), $options = array(), $writeLog = false)
    {
        $paramsFMT = array();
        foreach ($params as $key => $val) {
            $paramsFMT[] = $key . "=" . urlencode($val);
        }
        return self::curl_get_contents($url . ($paramsFMT ? ( "?" . join("&", $paramsFMT)) : ""), $options, $writeLog);
    }


    /**
     * @param $url
     * @param array $params
     * @param array $options
     * @param bool $writeLog
     * @return mixed
     */
    public static function http_post($url, $params = array(), $options = array(), $writeLog = false)
    {
        $paramsFMT = array();
        foreach ($params as $key => $val) {
            $paramsFMT[] = $key . "=" . urlencode($val);
        }
        $options[CURLOPT_POST] = 1;
        $options[CURLOPT_POSTFIELDS] = join("&", $paramsFMT);
        return self::curl_get_contents($url, $options, $writeLog);
    }
}