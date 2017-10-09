<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/10/9
 * Time: 15:01
 */

namespace common\logic;


use common\models\SkuParameterAndValue;
use common\models\SkuSku;
use yii\db\Exception;

/**
 * SKU 相关逻辑
 *
 * Class SkuLogic
 * @package common\logic
 */
class SkuLogic extends Instance
{
    /**
     * 删除SKU
     *
     * @param $skuId
     *
     * @return bool
     * @throws Exception
     */
    public function deleteSku($skuId)
    {
        $t = \Yii::$app->db->beginTransaction();
        try {
            $sku = SkuSku::findOne($skuId);
            $sku->delete();
            SkuParameterAndValue::deleteAll(['sku_id' => $skuId]);
            $t->commit();
        } catch (Exception $e) {
            $t->rollBack();
            throw $e;
        }
        return true;
    }
}