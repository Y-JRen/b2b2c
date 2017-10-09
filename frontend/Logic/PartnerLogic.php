<?php

namespace frontend\logic;

use common\models\Partner;
use yii\db\Query;

/**
 * Class PartnerLogic
 *
 * 前台厂商信息的logic 公共处理
 *
 * @package frontend\logic
 */
class PartnerLogic extends \common\logic\PartnerLogic
{
    // 说明 ： 0 表示不限 数值单位为万(价格区间)
    private $arrPriceInterval = [
        ['min_price' => 0, 'max_price' => 8, 'name' => '8万以下'],
        ['min_price' => 8, 'max_price' => 12, 'name' => '8 - 12万'],
        ['min_price' => 12, 'max_price' => 15, 'name' => '12 - 15万'],
        ['min_price' => 15, 'max_price' => 20, 'name' => '15 - 20万'],
        ['min_price' => 20, 'max_price' => 25, 'name' => '20 - 25万'],
        ['min_price' => 25, 'max_price' => 50, 'name' => '25 - 50万'],
        ['min_price' => 50, 'max_price' => 0, 'name' => '50万以上'],
    ];

    /**
     * 获取价格区间
     *
     * @return array
     */
    public function getPriceInterVal()
    {
        return $this->arrPriceInterval;
    }

    /**
     * 获取合作商信息
     *
     * @param integer $id 合作商ID
     * @param array $select 查询的字段
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getPartnerInfo($id, $select = [
        'id', 'name', 'address', 'logo',
        'contact_person', 'contact_phone', 'description'
    ])
    {
        $array = Partner::find()->select($select)->where(['id' => $id])->asArray()->one();
        if ($array && isset($array['id'])) $array['id'] = (int)$array['id'];
        return $array;
    }

    /**
     * 获取合作商的品牌车系信息
     *
     * @param integer $intPartnerId 合作商ID
     * @return array
     */
    public function getPartnerBrands($intPartnerId)
    {
        // 查询数据
        $query = (new Query())->select(['c.brand_id', 'c.brand_name', 'c.series_id', 'c.series_name'])
            ->from('sku_spu_car as c')
            ->innerJoin('sku_item as i', '`c`.`spu_id` = `i`.`spu_id`')
            ->where(['i.partner_id' => $intPartnerId])
            ->groupBy('c.series_id');
        $array = $query->all();
        $arrReturn = [];
        if ($array) {
            foreach ($array as $value) {
                $key = $value['brand_id'];
                // 添加品牌
                if (!isset($arrReturn[$key])) {
                    $arrReturn[$key] = [
                        'id' => $value['brand_id'],
                        'name' => $value['brand_name'],
                        'child' => []
                    ];
                }

                // 添加车系
                $arrReturn[$key]['child'][] = [
                    'id' => $value['series_id'],
                    'name' => $value['series_name']
                ];
            }

            // 处理为数组
            $arrReturn = array_values($arrReturn);
        }

        return $arrReturn;
    }

}