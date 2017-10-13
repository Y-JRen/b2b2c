<?php

namespace common\traits;

use Yii;

/**
 * Trait Redis redis 处理相关的功能，不使用yii-cache
 * @package common\traits
 */
trait Redis
{
    /**
     * @var string redis 保存数据的前缀
     */
    public static $prefix = 'E2B';

    /**
     * 获取缓存
     *
     * @param string $key 获取缓存的key
     * @return mixed
     */
    public function getCache($key)
    {
        $key = self::$prefix.':'.$key;
        $mixReturn = Yii::$app->redis->get($key);
        if ($mixReturn) {
            $mixReturn = \yii\helpers\Json::decode($mixReturn);
        }

        return $mixReturn;
    }

    /**
     * 设置缓存信息
     *
     * @param string $key 设置缓存的key
     * @param mixed $value 缓存的值
     * @return mixed
     */
    public function setCache($key, $value)
    {
        $key = self::$prefix.':'.$key;
        return Yii::$app->redis->set($key, \yii\helpers\Json::encode($value));
    }

    /**
     * 设置缓存信息,并设置有效时间
     * @param $key
     * @param $value
     * @param $time
     * @return mixed
     */
    public function setCacheTime($key, $value, $time)
    {
        $key = self::$prefix.':'.$key;
        return Yii::$app->redis->setex($key, \yii\helpers\Json::encode($value), $time);
    }

    /**
     * 删除缓存
     *
     * @param string $key 缓存的key
     * @return mixed
     */
    public function deleteCache($key)
    {
        $key = self::$prefix.':'.$key;
        return Yii::$app->redis->del($key);
    }
}