<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/28
 * Time: 14:27
 */

namespace backend\models\form;


use common\models\SkuItemFinancial;
use common\models\SkuParameterAndValue;
use common\models\SkuSku;
use yii\db\Exception;
use yii\helpers\ArrayHelper;


/**
 * spu item 表单
 *
 *
 * Class SpuItemForm
 * @package backend\models\form
 */
class SpuItemForm extends SpuForm
{
    const SCENARIO_SAVE_BASE = 'save_base';
    const SCENARIO_SAVE_INTRODUCE = 'save_introduce';


    /**
     * sku信息
     *
     * @var array
     */
    public $sku;
    
    /**
     * 金融方案
     *
     * @var array
     */
    public $item_financial;
    /**
     * 图片
     * @var array
     */
    public $images;
    
    public function rules()
    {
        return [
            [['sku', 'deposit', 'item_financial'], 'required','on'=>[self::SCENARIO_SAVE_BASE]],
            [['des'], 'required','on'=>[self::SCENARIO_SAVE_INTRODUCE]],
            ['deposit', 'integer'],
            ['sku', 'checkSku'],
            ['item_financial', 'each', 'rule' => ['integer']],
            ['images','each','rule' => ['string']]
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_SAVE_BASE => ['sku', 'deposit', 'item_financial'],
            self::SCENARIO_SAVE_INTRODUCE => ['name','subname','images','des']
        ];
    }

    public function beforeValidate()
    {
        switch ($this->getScenario()){
            case self::SCENARIO_SAVE_INTRODUCE:
                break;
            default:
                parent::beforeValidate();
                break;
        }
    }


    /**
     * label 信息
     *
     * @return array
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'item_financial' => '金融方案',
            'subname' => ' 商品副标题',
            'des' => '详情介绍',
        ]);
    }
    
    /**
     * 验证sku相关信息
     *
     * @param $attribute
     */
    public function checkSku($attribute)
    {
        if(!is_array($this->$attribute)) {
            $this->addError($attribute, '参数格式错误');
        }
        foreach ($this->$attribute as $value) {
            if (!isset($value['outer_color_label_id']) || !isset($value['outer_color_label_value']) ||
                !isset($value['outer_color_value_id']) || !isset($value['outer_color_value_value'])) {
                $this->addError($attribute, '外色参数错误');
            }
            if (!isset($value['inner_color_label_id']) || !isset($value['inner_color_label_value']) ||
                !isset($value['inner_color_value_id']) || !isset($value['inner_color_value_value'])) {
                $this->addError($attribute, '内色参数错误');
            }
        }
    }
    
    /**
     * 信息保存
     *
     * @return array|bool
     * @throws Exception
     */
    public function saveItem()
    {
        $res = false;
        switch ($this->getScenario()){
            case self::SCENARIO_SAVE_BASE:
                $res = $this->saveBase();
                break;
            case self::SCENARIO_SAVE_INTRODUCE:
                $res = $this->saveIntroduce();
                break;
            default:
                break;
        }
        return $res;
    }

    public function saveBase()
    {
        $t = \Yii::$app->db->beginTransaction();
        try{
            $this->save();
            $this->saveFinancial();
            $this->skuSave();
            $t->commit();
        } catch (Exception $e){
            $t->rollBack();
            throw $e;
        }
        return true;
    }
    
    /**
     * item 金融方案
     *
     * @todo 缺少首付和月供
     *
     * @return bool
     * @throws Exception
     */
    public function saveFinancial()
    {
        $data = [];
        foreach ($this->item_financial as $financial) {
            $data[] = [
                $this->spu_id,
                $financial,
                $this->id,
                $this->partner_id,
                date("Y-m-d H:i:s")
            ];
        }
        $rst = \Yii::$app->db->createCommand()->batchInsert(SkuItemFinancial::tableName(), [
            'spu_id', 'financial_id', 'item_id', 'partner_id', 'create_time'
        ],$data)->execute();
        if (!$rst) {
            throw new Exception('添加失败！');
        }
        return true;
    }
    
    /**
     * SKU 保存
     * @TODO create_person
     *
     * @return bool
     * @throws Exception
     */
    public function skuSave()
    {
        foreach ($this->sku as $val) {
            if ($val['id']) {
                $sku = SkuSku::findOne($val['id']);
                $sku->price = $val['price'];
                $sku->name = $val['name'];
                $sku->subname = $val['subname'];
                $sku->save();
            } else {
                $sku = new SkuSku();
                $sku->price = $val['price'];
                $sku->spu_id = $this->spu_id;
                $sku->name = $val['name'];
                $sku->subname = $val['subname'];
                $sku->partner_id = $this->partner_id;
                $sku->deposit = $this->deposit;
                $sku->item_id = $this->id;
                $sku->item_type_id = $this->type_id;
                $sku->spu_type_id = $this->spu_type_id;
                $sku->create_time = date("Y-m-d H:i:s");
                $sku->create_person = 'test';
                $sku->status = 1;
                if (!$sku->save()) {
                    throw new Exception(json_encode($sku->errors));
                }
                $this->addSkuBaseParameter($val['outer_color_label_id'], $val['outer_color_label_value'], $val['outer_color_value_id'], $val['outer_color_value_value'], $sku->id);
                $this->addSkuBaseParameter($val['inner_color_label_id'], $val['inner_color_label_value'], $val['inner_color_value_id'], $val['inner_color_value_value'], $sku->id);
        
            }
        }
        return true;
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
    
    public function afterFind()
    {
        parent::afterFind();
        $this->item_financial = ArrayHelper::getColumn($this->financial, 'financial_id');
        $this->sku = $this->skuDetail;
    }
    
    /**
     * 普通车相关的金融方案
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFinancial()
    {
        return $this->hasMany(SkuItemFinancial::className(), ['item_id' => 'id']);
    }
    
    /**
     * 关联SKU
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkuDetail()
    {
        return $this->hasMany(SkuSku::className(), ['spu_id' => 'spu_id']);
    }

    public function saveIntroduce()
    {
        $t = \Yii::$app->db->beginTransaction();
        try{
            $this->save();
            //$this->saveAttachment();
            $t->commit();
        } catch (Exception $e){
            $t->rollBack();
            throw $e;
        }
        return true;
    }

    public function saveAttachment()
    {
       foreach($this->images as $v){

       }
    }
}