<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/29
 * Time: 17:12
 */

namespace common\logic;

use common\models\CarBaseConfColor;
use common\models\SkuBaseParameter;
use common\models\SkuBaseParameterValue;
use yii\db\Exception;

/**
 * Spu 相关逻辑
 *
 * Class SpuLogic
 * @package common\logic
 */
class SpuLogic extends Instance
{
    /**
     * 新建spu时，增加sku内外色
     *
     * @param $spuId
     * @param $carId
     */
    public function addSpuColor($spuId, $carId)
    {
        $colors = CarBaseConfColor::find()->where(['car_id' => $carId])->all();
        foreach ($colors as $color)
        {
            $parameterLabel = $color->type ? '内色' : '外色';
            $this->addSkuBaseParameter($parameterLabel, $color->name, $spuId);
        }
    }
    
    /**
     * @param $parameterLabel
     * @param $parameterValue
     * @param $spuId
     */
    public function addSkuBaseParameter($parameterLabel, $parameterValue, $spuId)
    {
        $t = \Yii::$app->db->beginTransaction();
        try{
            $skuBaseParameter = $this->saveBaseParameter($parameterLabel, $spuId);
            $this->saveBaseParameterValue($parameterValue, $skuBaseParameter->id);
            $t->commit();
        } catch (Exception $e) {
            $t->rollBack();
        }
    }
    
    /**
     * label
     *
     * @param $parameterLabel
     * @param $spuId
     *
     * @return SkuBaseParameter
     * @throws Exception
     */
    public function saveBaseParameter($parameterLabel, $spuId)
    {
        $skuBaseParameter = new SkuBaseParameter();
        $skuBaseParameter->spu_id = $spuId;
        $skuBaseParameter->name = $parameterLabel;
        $skuBaseParameter->create_time = date('Y-m-d H:i:s');
        if (!$skuBaseParameter->save()) {
            throw new Exception('保存失败');
        }
        return $skuBaseParameter;
    }
    
    /**
     * value
     *
     * @param $parameterValue
     * @param $parameterId
     *
     * @return SkuBaseParameterValue
     * @throws Exception
     */
    public function saveBaseParameterValue($parameterValue, $parameterId)
    {
        $skuBaseParameterValue = new SkuBaseParameterValue();
        $skuBaseParameterValue->parameter_id = $parameterId;
        $skuBaseParameterValue->name = $parameterValue;
        $skuBaseParameterValue->create_time = date('Y-m-d H:i:s');
        if (!$skuBaseParameterValue->save()) {
            throw new Exception('保存失败');
        }
        return $skuBaseParameterValue;
    }
    
    
}