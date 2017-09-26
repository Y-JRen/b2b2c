<?php
/**
 * 单例基类，继承该类的类只能单例获取实例化对象.
 * User: 雕
 * Date: 2017/9/13
 * Time: 18:14
 */

namespace common\logic;

/**
 * Class Instance
 * @package common\logic
 */
abstract class Instance
{
    static $instance = [];

    /**
     * 私有化构造函数，禁止使用new的方式去实例化对象，以免破坏单例模式
     */
    private function __construct(){}

    /**
     * 私有化clone方法，禁止复制对象，以免破坏单例模式
     */
    private function __clone(){}

    /**
     * 创建实例
     * @param bool $flush
     * @return static
     */
    public static function instance($flush = false)
    {
        $name = get_called_class();
        if ($flush || !isset(self::$instance[$name])) {
            self::$instance[$name] = new $name;
        }
        return self::$instance[$name];
    }
    
    /**
     * 销毁实例
     */
    public static function destructInstance()
    {
        $name = get_called_class();
        if (isset(self::$instance[$name])) {
            unset(self::$instance[$name]);
        }
    }
}