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
     * @param integer $intItemId item ID
     * @return array
     */
    public function getItemParameters($intItemId)
    {
        // 获取全部商品ID
        $arrIds = $this->getItemSku($intItemId, 'id');
        $arrReturn = [];
        if ($arrIds) {
            // 查询出全部属性信息
            $arrParameters = SkuParameterAndValue::find()->where(['sku_id' => $arrIds])->asArray()->all();
            foreach ($arrParameters as $value) {
                if (!isset($arrReturn[$value['sku_id']])) {
                    $arrReturn[$value['sku_id']] = [];
                }

                $arrReturn[$value['sku_id']][] = [
                    'parameter_id' => (int)$value['parameter_id'],
                    'parameter_name' => $value['parameter_name'],
                    'value_id' => (int)$value['value_id'],
                    'value_name' => $value['value_name'],
                ];
            }
        }

        return $arrReturn;
    }

    /**
     * 获取item 下面所有的商品(可以指定获取某个字段)
     *
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