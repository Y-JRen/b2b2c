<?php

namespace common\logic;

use common\models\Partner;
use common\models\SkuParameterAndValue;
use common\models\SkuSku;
use common\traits\Redis;
use yii\db\Query;

/**
 * SKU item 相关逻辑
 *
 * Class SkuLogic
 * @author liujx
 * @package common\logic
 */
class SkuItemLogic extends Instance
{
    /**
     * 使用缓存功能
     */
    use Redis;

    /**
     * 获取item 下面所有商品和属性信息
     *
     * @author liujx
     * @param integer $intItemId item ID
     * @param integer $intSkuId 默认选中的值(sku_id)
     * @return array
     */
    public function getItemParameters($intItemId, $intSkuId)
    {
        // 获取全部商品ID
        $arrIds = $this->getItemSku($intItemId, 'id');
        $arrReturn = [];
        if ($arrIds) {
            // 查询出全部属性信息
            $arrReturn = SkuParameterAndValue::find()
                ->select(['parameter_id', 'parameter_name', 'value_id', 'value_name', 'sku_id'])
                ->where(['sku_id' => $arrIds])
                ->asArray()
                ->all();

            $array = [];

            // 通过属性值排序
            foreach ($arrReturn as $value) {
                if (!isset($array[$value['parameter_id']])) {
                    $array[$value['parameter_id']] = [
                        'parameter_id' => (int)$value['parameter_id'],
                        'parameter_name' => $value['parameter_name'],
                        'values' => [],
                    ];
                }

                // 通过属性值去唯一
                $array[$value['parameter_id']]['values'][] = [
                    'value_id' => (int)$value['value_id'],
                    'value_name' => $value['value_name'],
                    'sku_id' => (int)$value['sku_id'],
                ];
            }

            $arrReturn = $this->formatArrayParameter(array_values($array), $intSkuId);
        }

        return $arrReturn;
    }

    /**
     * 格式化item_id 给前台调用
     *
     * @author liujx
     * @param array $array 查询到的数据（通过属性值分组了的数组）
     * @param integer $intSkuId
     * @return mixed
     */
    public function formatArrayParameter($array, $intSkuId)
    {
        // 第一步，统一属性的不同属性值分组 value_id
        foreach ($array as $key => $value) {
            $arrValueIds = [];
            foreach ($value['values'] as $val) {
                if (!isset($arrValueIds[$val['value_id']])) {
                    $arrValueIds[$val['value_id']] = [
                        'value_id' => $val['value_id'],
                        'value_name' => $val['value_name'],
                        'sku_ids' => [],
                    ];
                }

                $arrValueIds[$val['value_id']]['sku_ids'][] = $val['sku_id'];
            }

            $array[$key]['values'] = array_values($arrValueIds);
        }

        $arrSkuIds = $arrTmp = [];
        // 第二步，处理第一个属性的默认选中
        $first = array_shift($array);
        foreach ($first['values'] as &$value) {
            $this->selected($value, $arrSkuIds, $intSkuId);
        }
        unset($value);

        // 第三步，处理下一层的属性选中和剔除该层不是上一层选中的属性
        foreach ($array as $key => &$value) {
            foreach ($value['values'] as $k => &$val) {
                $has = array_intersect($val['sku_ids'], $arrSkuIds);
                if (empty($has)) {
                    unset($array[$key]['values'][$k]);
                } else {
                    $this->selected($val, $arrTmp, $intSkuId, $has);
                }
            }
            unset($val);

            $value['values'] = array_values($value['values']);
            $arrSkuIds = $arrTmp;
        }
        unset($value);

        array_unshift($array, $first);
        return $array;
    }

    /**
     * 确定默认选中的skuId
     *
     * @author liujx
     * @param array $value 处理的values信息
     * @param array $arrHas 这一层下面的下一层的sku_ids(当前sku_id所在的属性中的sku_id)
     * @param integer $intSkuId 当前的sku_id
     * @param array $has
     */
    public function selected(&$value, &$arrHas, $intSkuId, $has = [])
    {
        $value['sku_id'] = $value['sku_ids'][0];
        $value['selected'] = false;
        if (in_array($intSkuId, $value['sku_ids'])) {
            $arrHas = $value['sku_ids'];
            $value['selected'] = true;
            if (count($arrHas) > 1 && $value['sku_id'] == $intSkuId) {
                $value['sku_id'] = $arrHas[1];
            }
        } else {
            if ($has) $value['sku_id'] = array_shift($has);
        }
    }

    /**
     * 获取item 下面所有的商品(可以指定获取某个字段)
     *
     * @author liujx
     * @param integer $intItemId itemID
     * @param string $column 获取某个字段
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getItemSku($intItemId, $column = '')
    {
        $array = SkuSku::find()->where(['item_id' => $intItemId])->asArray()->all();
        if ($column) {
            $array = array_column($array, $column);
        }

        return $array;
    }

    /**
     * 搜索 item 信息
     *
     * @author liujx
     * @param array $params 查询信息
     * @return array ['total' => '数据条数', 'lists' => '数据']
     */
    public function searchItem($params)
    {
        // 处理查询条件
        if (empty($params['where'])) $params['where'] = [];

        // 查询字段
        if (empty($params['select'])) {
            $params['select'] = [
                'i.id', 'i.partner_id', 'i.image',
                'c.brand_id', 'c.brand_name', 'c.series_id', 'c.series_name', 'c.car_type_name',
                'c.spu_id', 'c.car_type_id'
            ];
        }

        // 查询数据
        $query = (new Query())->select($params['select'])
            ->from('sku_spu_car as c')
            ->innerJoin('sku_item as i', '`c`.`spu_id` = `i`.`spu_id`')
            ->where($params['where']);

        // 数据总条数
        $total = $query->count();

        // 分页查询
        if (isset($params['offset'])) $query->offset($params['offset']);
        if (isset($params['limit'])) $query->limit($params['limit']);

        // 查询数据
        $array = $query->all();

        if ($array) {

            // 获取item_id 对应的sku_id
            $itemIds = array_column($array, 'id');
            // 查询sku_id 信息
            $arrSku = (new Query())->from('sku_sku')
                ->select('id')
                ->where(['item_id' => $itemIds])
                ->groupBy('item_id')
                ->indexBy('id')
                ->all();

            // 获取对应的车型信息
            $arrCarIds = array_column($array, 'car_type_id');
            $arrCars = (new Query())->from('car_brand_son_type_info')
                ->select(['factory_price', 'car_brand_son_type_id'])
                ->where(['car_brand_son_type_id' => $arrCarIds])
                ->indexBy('car_brand_son_type_id')
                ->all();

            $arrPartnerIds = array_column($array, 'partner_id');
            $arrPartner = Partner::find()->select(['name', 'id'])
                ->where(['id' => $arrPartnerIds])
                ->asArray()
                ->indexBy('id')
                ->all();

            foreach ($array as &$value) {
                // 存在sku_id 信息
                if (isset($arrSku[$value['id']])) {
                    $value['sku_id'] = (int)$arrSku[$value['id']]['id'];
                } else {
                    $value['sku_id'] = 0;
                }

                // 指导价
                if (isset($arrCars[$value['car_type_id']])) {
                    $value['factory_price'] = $arrCars[$value['car_type_id']]['factory_price'];
                } else {
                    $value['factory_price'] = '0';
                }

                // 厂商名称
                if (isset($arrPartner[$value['partner_id']])) {
                    $value['partner_name'] = $arrPartner[$value['partner_id']]['name'];
                } else {
                    $value['partner_name'] = '';
                }

                $value['spu_id'] = (int)$value['spu_id'];
                $value['partner_id'] = (int)$value['partner_id'];
                $value['id'] = (int)$value['id'];
            }

            unset($value);
        }

        // 返回数据
        return [
            'total' => $total,
            'lists' => $array
        ];
    }
}