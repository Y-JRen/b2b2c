<?php

namespace frontend\logic;

use common\models\Partner;
use Yii;
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
    /**
     * @var array 价格区间
     */
    private $arrPriceInterval = [];

    public function __construct()
    {
        $this->arrPriceInterval = Yii::$app->params['arrPriceInterval'];
    }

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