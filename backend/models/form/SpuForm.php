<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/27
 * Time: 17:08
 */

namespace backend\models\form;


use common\logic\CarLogic;
use common\models\SkuSpu;
use common\models\SkuSpuCar;
use common\models\SkuSpuPartner;
use yii\db\Exception;

/**
 * Class SpuForm
 * @package backend\models\form
 */
class SpuForm extends SkuSpu
{
    /**
     * 经销商ID
     *
     * @var int
     */
    public $partner_id;
    
    /**
     * 一级类目
     * 1 - 中规车
     * 2- 平行进口
     *
     * @var int
     */
    public $category = 1;
    
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
            [['partner_id', 'category', 'brand_id', 'factory_id', 'series_id', 'car_id'], 'required'],
            [['partner_id', 'category', 'brand_id', 'factory_id', 'series_id', 'car_id'], 'integer'],
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
    
    public function attributeLabels()
    {
        return [
            'partner_id' => '经销商',
            'category' => '一级类目',
            'brand_id' => '品牌',
            'factory_id' => '厂商',
            'series_id' => '车系',
            'car_id' => '车型',
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
        if ($spuId = $this->checkSpu()) {
            return $spuId;
        }
        $t = \Yii::$app->db->beginTransaction();
        try {
            $this->spuSave();
            $this->spuCarSave();
            $this->spuPartnerSave();
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
            return $spu->spu_id;
        }
        
        return false;
    }
    
    /**
     * spu 基础信息保存
     *
     * @return true
     * @throws Exception
     */
    public function spuSave()
    {
        $this->create_time = date("Y-m-d H:i:s");
        $this->name = date("Y-m-d H:i:s");
        $this->create_time = date("Y-m-d H:i:s");
        if (!$this->save()) {
            throw new Exception('保存失败', $this->errors);
        }
        
        return true;
    }
    
    /**
     * spu 车型
     *
     * @return bool
     * @throws Exception
     */
    public function spuCarSave()
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
        $spuCar->spu_id = $this->id;
        if (!$spuCar->save()) {
            throw new Exception('保存失败', $spuCar->errors);
        }
        
        return true;
    }
    
    /**
     * spu 合作商
     *
     * @return bool
     * @throws Exception
     */
    public function spuPartnerSave()
    {
        $spuPartner = new SkuSpuPartner();
        $spuPartner->spu_id = $this->id;
        $spuPartner->partner_id = $this->partner_id;
        $spuPartner->des = '';
        $spuPartner->create_time = date("Y-m-d H:i:s");
        if (!$spuPartner->save()) {
            throw new Exception('保存失败', $spuPartner->errors);
        }
        
        return true;
    }
}