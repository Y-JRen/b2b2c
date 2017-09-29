<?php

namespace common\logic;

use common\traits\Redis;

/**
 * Class CarBaseConfigLogic 车型基础配置信息
 * @package common\logic
 */
class CarBaseConfLogic
{
    // 使用缓存信息
    use Redis;

    /**
     * @var array 配置的表信息
     */
    public static $arrTables = [
        'car_base_conf_air',        // 车辆配置- 空调/冰箱
        'car_base_conf_basic',      // 车辆配置- 基本信息
        'car_base_conf_body',       // 车辆配置-车身配置
        'car_base_conf_brake',      // 车辆配置-车轮制动
        'car_base_conf_chassis',    // 车辆配置-底盘转向
        'car_base_conf_color',      // 车辆配置-颜色(该表的唯一性 id 不能以 car_id 唯一)
        'car_base_conf_elec',       // 车辆配置- 电动机信息
        'car_base_conf_engine',     // 车辆配置-发动机
        'car_base_conf_extend',     // 车辆配置-扩展
    ];

}