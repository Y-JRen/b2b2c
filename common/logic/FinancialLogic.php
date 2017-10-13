<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/28
 * Time: 17:56
 */

namespace common\logic;
use common\models\FinancialProgram;
use common\traits\Redis;
use yii\db\Query;


/**
 * 金融方案相关逻辑
 *
 * Class FinancialLogic
 * @package common\logic
 */
class FinancialLogic extends Instance
{
    /**
     * 使用缓存功能
     */
    use Redis;

    public function getPartnerFinancial($partnerId)
    {
        return [
            1 => 'test',
            2 => 'test2',
        ];
    }

    public function getFinancialBySkuId($intSkuId, $intItemId)
    {
        // 获取金融信息ID
        $ids = $this->getFinancialIdsBySkuId($intSkuId);
        if (empty($ids)) {
            $ids = $this->getFinancialIdsByItemId($intItemId);
        }

        // 获取金融信息
        $array = FinancialProgram::find()->select([
            'id', 'no', 'type', 'name', 'des'
        ])->where([
            'id' => $ids,
            'status' => 1,
            'is_delete' => 0,
        ])->asArray()->all();

        if ($array) {
            foreach ($array as &$value) {
                $value['id'] = (int)$value['id'];
                $value['type'] = (int)$value['type'];
            }

            unset($value);
        } else {
            $array = null;
        }

        return $array;
    }

    /**
     * 获取商品的金融方法ID信息
     *
     * @param integer $intSkuId 商品ID
     * @return array|mixed
     */
    public function getFinancialIdsBySkuId($intSkuId)
    {
        $key = 'sku_sku_financial:sku_id:'.$intSkuId;
        $mixReturn = $this->getCache($key);
        if (!$mixReturn) {
            $mixReturn = (new Query())->from('sku_sku_financial')
                ->select(['financial_id'])->where(['sku_id' => $intSkuId])->column();
            $this->setCache($key, $mixReturn);
        }

        return $mixReturn;
    }

    /**
     * 获取item 的金融方案信息
     *
     * @param integer $intItemId itemId
     * @return array|mixed
     */
    public function getFinancialIdsByItemId($intItemId)
    {
        $key = 'sku_item_financial:item_id:'.$intItemId;
        $mixReturn = $this->getCache($key);
        if (!$mixReturn) {
            $mixReturn = (new Query())->from('sku_item_financial')
                ->select(['financial_id'])->where(['item_id' => $intItemId])->column();
            $this->setCache($key, $mixReturn);
        }

        return $mixReturn;
    }
}