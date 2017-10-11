<?php

namespace common\logic;

use common\traits\Redis;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class CarBaseConfigLogic 车型基础配置信息
 * @package common\logic
 */
class CarBaseConfLogic extends Instance
{
    // 使用缓存信息
    use Redis;

    /**
     * @var array 配置的表信息
     */
    public static $arrTables = [
        'car_base_conf_air' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置- 空调/冰箱'
        ],
        'car_base_conf_basic' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置- 基本信息'
        ],
        'car_base_conf_body' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-车身配置',
        ],
        'car_base_conf_brake' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-车轮制动'
        ],
        'car_base_conf_chassis' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-底盘转向'
        ],
        'car_base_conf_color' => [
            'pk' => 'CAR_ID',           // (该表的唯一性 id 不能以 car_id 唯一)
            'title' => '车辆配置-颜色',
            'type' => 'all',
            'group' => 'type'
        ],
        'car_base_conf_elec' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置- 电动机信息'
        ],
        'car_base_conf_engine' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-发动机'
        ],
        'car_base_conf_extend' => [
            'pk' => 'CAR_BRAND_SON_TYPE_ID',
            'title' => '车辆配置-扩展',
            'type' => 'all'
        ],
        'car_base_conf_gearbox' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-变速箱信息'
        ],
        'car_base_conf_glass' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-玻璃/后视镜'
        ],

        'car_base_conf_in' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-内部配置'
        ],
        'car_base_conf_light' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置- 灯光配置'
        ],

        'car_base_conf_media' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-多媒体配置'
        ],

        'car_base_conf_oper' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-操控配置'
        ],

        'car_base_conf_outside' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-车轮制动'
        ],

        'car_base_conf_safe' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-安全装备'
        ],

        'car_base_conf_seat' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置-座椅配置'
        ],

        'car_base_conf_tech' => [
            'pk' => 'CAR_ID',
            'title' => '车辆配置- 高科技配置'
        ],
    ];

    /**
     * 获取配置说明信息
     *
     * @param string $type
     * @return array
     */
    public function getConfigDesc($type = 'all')
    {
        $arrReturn = [];
        if ($type === 'all') {
            foreach (self::$arrTables as $key => $value) {
                $arrReturn[$key] = $value['title'];
            }
        } else {
            $arrReturn[$type] = ArrayHelper::getValue(self::$arrTables, $type.'.title');
        }

        return $arrReturn;
    }

    /**
     * 通过名称获取缓存信息
     *
     * @param string $key 获取的表名称(all 表示全部)
     * @param mixed $pk  主键值
     * @return array|bool|mixed|null
     */
    public function getConfig($key, $pk)
    {
        $arrReturn = $key === 'all' ? $this->getAllConfig($pk) : $this->getConfigByKey($key, $pk);
        return $arrReturn;
    }

    /**
     * 通过名称获取缓存信息
     *
     * @param string $key 获取的表名称
     * @param mixed $pk  主键值
     * @return array|bool|mixed|null
     */
    public function getConfigByKey($key, $pk)
    {
        $mixReturn = null;
        $table = ArrayHelper::getValue(self::$arrTables, $key);
        if ($table) {
            $strKey = $key.':'.$table['pk'].':'.$pk;
            $mixReturn = $this->getCache($strKey);

            // 缓存中没有，那么查询数据
            if (!$mixReturn) {
                $query = (new Query())->from($key)->where([$table['pk'] => $pk]);
                if (isset($table['type']) && $table['type'] === 'all') {
                    $mixReturn = $query->all();
                } else {
                    $mixReturn = $query->one();
                }

                // 处理是否需要分组
                if ($mixReturn && !empty($table['group'])) {
                    $mixReturn = ArrayHelper::index($mixReturn, null, $table['group']);
                }

                // 生成缓存信息
                if ($mixReturn) {
                    $this->setCache($strKey, $mixReturn);
                } else {
                    $mixReturn = null;
                }
            }
        }

        return $mixReturn;
    }

    /**
     * 通过主键获取全部的配置信息
     *
     * @param mixed $pk 主键值
     * @return array
     */
    public function getAllConfig($pk)
    {
        $arrReturn = [];
        foreach (self::$arrTables as $key => $value) {
            $arrReturn[$key] = $this->getConfigByKey($key, $pk);
        }

        return $arrReturn;
    }
}