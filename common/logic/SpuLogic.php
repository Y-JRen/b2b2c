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
use common\models\SkuParameterAndValue;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use common\traits\Redis;

/**
 * Spu 相关逻辑
 *
 * Class SpuLogic
 * @package common\logic
 */
class SpuLogic extends Instance
{
    /**
     * 使用缓存功能信息
     */
    use Redis;

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
    
    /**
     * sku信息， 如内外色
     *
     * @param $skuId
     *
     * @return array
     */
    public function getParameter($skuId)
    {
        $param = SkuParameterAndValue::find()->where([
            'sku_id' => $skuId
        ])->all();
        
        return ArrayHelper::map($param, 'parameter_name', 'value_name');
    }

    /**
     * 获取 spu 存在的属性信息
     *
     * @param  integer $intSpuId spu id
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    public function getParameterBySpuId($intSpuId)
    {
        $key = 'sku_sku_base_parameter:sup_id:'.$intSpuId;
        $arrReturn = $this->getCache($key);
        if (!$arrReturn) {
            $arrReturn = SkuBaseParameter::find()->select(['id', 'name'])
                ->where(['spu_id' => $intSpuId])
                ->asArray()
                ->all();
            if ($arrReturn) {
                foreach($arrReturn as &$value) {
                    $value['id'] = (int)$value['id'];
                }

                unset($value);
                $this->setCache($key, $arrReturn);
            }
        }

        return $arrReturn;
    }

    /**
     * 获取属性对应的值的信息
     *
     * @param integer $intParameterId 属性名称ID
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    public function getParameterValueByParameterId($intParameterId)
    {
        $key = 'sku_sku_base_parameter_value:parameter_id:'.$intParameterId;
        $mixReturn = $this->getCache($key);
        if (!$mixReturn) {
            $mixReturn = SkuBaseParameterValue::find()->select(['id', 'name'])
                ->where(['parameter_id' => $intParameterId])
                ->asArray()
                ->all();
            if ($mixReturn) {
                foreach($mixReturn as &$value) {
                    $value['id'] = (int)$value['id'];
                }

                unset($value);
                $this->setCache($key, $mixReturn);
            }
        }

        return $mixReturn;
    }

    /**
     * 获取sup 属性信息和所有属性值信息
     * @param integer $intSupId
     * @return array 返回['id' => '属性名称id', 'name' => '属性名称', 'values' => '包含的对应属性值信息']
     */
    public function getParameterValueBySupId($intSupId)
    {
        $arrReturn = [];
        $mixReturn = $this->getParameterBySpuId($intSupId);
        if ($mixReturn) {
            foreach ($mixReturn as $key => $value) {
                $arrReturn[] = [
                    'id' => (int)$value['id'],
                    'name' => $value['name'],
                    'values' => $this->getParameterValueByParameterId($value['id']),
                ];
            }
        }

        return $arrReturn;
    }
}