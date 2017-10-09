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
     *
     * @return bool
     */
    public function addSpuColor($spuId, $carId)
    {
        //$spuId 已存在，不新增
        if (SkuBaseParameter::find()->where(['spu_id' => $spuId])->one()) {
            return true;
        }
        $colors = CarBaseConfColor::find()->where(['car_id' => $carId])->all();
        foreach ($colors as $color)
        {
            $parameterLabel = $color->type ? '内色' : '外色';
            $this->addSkuBaseParameter($parameterLabel, $color->name, $spuId);
        }
        return true;
    }
    
    /**
     * @param $parameterLabel
     * @param $parameterValue
     * @param $spuId
     */
    private function addSkuBaseParameter($parameterLabel, $parameterValue, $spuId)
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
    private function saveBaseParameter($parameterLabel, $spuId)
    {
        if ($skuBaseParameter = SkuBaseParameter::find()->where(['spu_id' => $spuId, 'name' => $parameterLabel])->one()) {
            return $skuBaseParameter;
        }
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
    private function saveBaseParameterValue($parameterValue, $parameterId)
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
    
    /**
     * @param $spuId
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSpuColorSelect($spuId)
    {
        $parameter = SkuBaseParameter::find()->where(['spu_id' => $spuId])->all();
        return $parameter;
    }
}