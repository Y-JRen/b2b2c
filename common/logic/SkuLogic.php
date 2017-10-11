<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/10/9
 * Time: 15:01
 */

namespace common\logic;


use backend\models\form\SpuItemForm;
use common\models\CarBrandSonTypeInfo;
use common\models\SkuParameterAndValue;
use common\models\SkuSku;
use common\traits\Redis;
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
     * 使用缓存功能
     */
    use Redis;

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
    
    /**
     * @TODO create_person
     *
     * @param SpuItemForm $spu
     * @param $data
     *
     * @return bool|SkuSku
     * @throws Exception
     */
    public function addSku($spu, $data)
    {
        if (!isset($data['outer_color_label_id']) || !isset($data['outer_color_label_value']) ||
            !isset($data['outer_color_value_id']) || !isset($data['outer_color_value_value'])) {
            return false;
        }
        if (!isset($data['inner_color_label_id']) || !isset($data['inner_color_label_value']) ||
            !isset($data['inner_color_value_id']) || !isset($data['inner_color_value_value'])) {
            return false;
        }
        $sku = new SkuSku();
        $guidePrice = CarBrandSonTypeInfo::findOne($spu->car_id)->factory_price * 10000;
        $sku->price = $data['price'] ?? $guidePrice;
        $sku->spu_id = $spu->spu_id;
        $sku->name = isset($data['name']) ? $data['name'] : $spu->name;
        $sku->subname = isset($data['subname']) ? $data['subname'] : '';
        $sku->partner_id = $spu->partner_id;
        $sku->deposit = $spu->deposit;
        $sku->item_id = $spu->id;
        $sku->item_type_id = $spu->item_type_id;
        $sku->spu_type_id = $spu->spu_type_id;
        $sku->create_time = date("Y-m-d H:i:s");
        $sku->create_person = 'test';
        $sku->status = 1;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($sku->save()) {
                //外色
                $this->addSkuBaseParameter($data['outer_color_label_id'], $data['outer_color_label_value'], $data['outer_color_value_id'], $data['outer_color_value_value'], $sku->id);
                //内色
                $this->addSkuBaseParameter($data['inner_color_label_id'], $data['inner_color_label_value'], $data['inner_color_value_id'], $data['inner_color_value_value'], $sku->id);
            } else {
                throw new Exception(json_encode($sku->errors));
            }
            $transaction->commit();
        
            return $sku;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    
    /**
     * 基础信息, sku 内外色
     *
     * @param int $parameterId
     * @param string $parameterNme
     * @param int $valueId
     * @param string $valueName
     * @param int $skuId
     *
     * @return SkuParameterAndValue
     * @throws Exception
     */
    public function addSkuBaseParameter($parameterId, $parameterNme, $valueId, $valueName, $skuId)
    {
        $skuParameterAndValue = new SkuParameterAndValue();
        $skuParameterAndValue->parameter_id = $parameterId;
        $skuParameterAndValue->parameter_name = $parameterNme;
        $skuParameterAndValue->value_id = $valueId;
        $skuParameterAndValue->value_name = $valueName;
        $skuParameterAndValue->sku_id = $skuId;
        if (!$skuParameterAndValue->save()) {
            throw new Exception("保存失败", $skuParameterAndValue->errors);
        }
        return $skuParameterAndValue;
    }
}