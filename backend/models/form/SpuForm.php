<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/27
 * Time: 17:08
 */

namespace backend\models\form;


use common\logic\CarLogic;
use common\logic\SpuLogic;
use common\logic\StoreLogic;
use common\models\SkuItem;
use common\models\SkuSpu;
use common\models\SkuSpuCar;
use yii\db\Exception;

/**
 * Class SpuForm
 *
 * @package backend\models\form
 *
 * @property SkuSpu spu
 * @property SkuSpuCar spuCar
 */
class SpuForm extends SkuItem
{
    public $type_id = 1;
    
    /**
     * 品牌
     *
     * @var int
     */
    public $brand_id;
    public $brand_name;
    
    /**
     * 厂商
     *
     * @var int
     */
    public $factory_id;
    public $factory_name;
    
    /**
     * 车系
     *
     * @var int
     */
    public $series_id;
    public $series_name;
    
    /**
     * 车型
     *
     * @var int
     */
    public $car_id;
    public $car_name;
    
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['partner_id', 'brand_id', 'factory_id', 'series_id', 'car_id', 'spu_type_id', 'item_type_id'], 'required'],
            [['partner_id', 'brand_id', 'factory_id', 'series_id', 'car_id'], 'integer'],
            ['name', 'string'],
            ['type_id', 'default', 'value' => 1],
        ];
    }
    
    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $carLogic = CarLogic::instance();
        if ($this->brand_id && $this->car_id && $this->series_id) {
            $this->name = $carLogic->getBrandName($this->brand_id) . '-' . $carLogic->getSeriesName($this->series_id) . '-' . $carLogic->getCarName($this->car_id);
        }
        return parent::beforeValidate();
    }
    
    /**
     * label
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'spu_id' => '商品ID',
            'partner_id' => '经销商',
            'category' => '一级类目',
            'brand_id' => '品牌',
            'factory_id' => '厂商',
            'series_id' => '车系',
            'car_id' => '车型',
            'name' => '商品名称',
            'create_time' => '创建时间',
            'spu_type_id' => '一级类目',
            'item_type_id' => '商品类型',
        ];
    }
    
    /**
     * spu保存
     *
     * @return bool|int|mixed
     * @throws Exception
     */
    public function saveAll()
    {
        if (!$this->validate()) {
            return $this->errors;
        }
        $t = \Yii::$app->db->beginTransaction();
        try {
            //检查spu是否存在
            if (!$spu = $this->checkSpu()) {
                $spu = $this->spuSave();
            }
            $this->spuPartnerSave($spu);

            // 新增的时候同步合作商的门店信息
            StoreLogic::instance()->synchronizedStoresToSkuItem($this->id, $this->spu_id, $this->partner_id);

            $t->commit();
            
            return $this->id;
        } catch (Exception $e) {
            $t->rollBack();
            throw $e;
        }
    }
    
    /**
     * 检测spu 是否存在
     *
     * @return array|bool|SkuSpu|null|\yii\db\ActiveRecord
     */
    public function checkSpu()
    {
        if ($spu = SkuSpuCar::find()->where([
            'brand_id' => $this->brand_id,
            'factory_id' => $this->factory_id,
            'series_id' => $this->series_id,
            'car_type_id' => $this->car_id,
        ])->one()) {
            return $spu;
        }
        
        return false;
    }
    
    /**
     * spu 基础信息保存
     *
     * @return SkuSpu
     * @throws Exception
     */
    public function spuSave()
    {
        $spu = new SkuSpu();
        $spu->name = $this->name;
        $spu->type_id = $this->type_id;
        $spu->create_time = date("Y-m-d H:i:s");
        if (!$spu->save()) {
            throw new Exception('保存失败', $spu->errors);
        }
        // 同步车型内外色
        SpuLogic::instance()->addSpuColor($spu->id, $this->car_id);
        //spu车型基础信息
        $this->spuCarSave($spu);
        return $spu;
    }
    
    /**
     * spu车型基础信息
     *
     * @param SkuSpu $spu
     *
     * @return bool
     * @throws Exception
     */
    public function spuCarSave($spu)
    {
        $carLogic = CarLogic::instance();
        $spuCar = new SkuSpuCar();
        $spuCar->brand_id = $this->brand_id;
        $spuCar->brand_name = $carLogic->getBrandName($this->brand_id);
        $spuCar->factory_id = $this->factory_id;
        $spuCar->factory_name = $carLogic->getFactoryName($this->factory_id);
        $spuCar->series_id = $this->series_id;
        $spuCar->series_name = $carLogic->getSeriesName($this->series_id);
        $spuCar->car_type_id = $this->car_id;
        $spuCar->car_type_name = $carLogic->getCarName($this->car_id);
        $spuCar->spu_id = $spu->id;
        if (!$spuCar->save()) {
            throw new Exception('保存失败', $spuCar->errors);
        }
        
        return true;
    }
    
    /**
     * spu 合作商
     *
     * @param SkuSpu $spu
     *
     * @return bool
     * @throws Exception
     */
    public function spuPartnerSave($spu)
    {
        $this->spu_id = $spu->id;
        $this->des = '';
        $this->create_time = date("Y-m-d H:i:s");
        if (!$this->save()) {
            throw new Exception('保存失败', $this->errors);
        }
        
        return true;
    }
    
    /**
     * spu-car
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpuCar()
    {
        return $this->hasOne(SkuSpuCar::className(), ['spu_id' => 'spu_id']);
    }
    
    
    /**
     * spu-partner
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpu()
    {
        return $this->hasOne(SkuSpu::className(), ['id' => 'spu_id']);
    }
    
    /**
     * 关联表数据肤质成员变量
     *
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->brand_id = $this->spuCar->brand_id;
        $this->brand_name = $this->spuCar->brand_name;
        $this->factory_id = $this->spuCar->factory_id;
        $this->factory_name = $this->spuCar->factory_name;
        $this->series_id = $this->spuCar->series_id;
        $this->series_name = $this->spuCar->series_name;
        $this->car_id = $this->spuCar->car_type_id;
        $this->car_name = $this->spuCar->car_type_name;
    }
}