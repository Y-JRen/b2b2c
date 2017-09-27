<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/14
 * Time: 15:11
 */

namespace common\logic;


use common\models\CarBaseConfColor;
use common\models\CarBrandFactoryInfo;
use common\models\CarBrandInfo;
use common\models\CarBrandSonTypeInfo;
use common\models\CarBrandTypeInfo;
use common\models\CarFactoryInfo;
use yii\helpers\ArrayHelper;

/**
 * 品牌、厂商、车型、车系相关逻辑
 *
 * Class CarLogic
 * @package backend\logic
 */
class CarLogic extends Instance
{
    /**
     * 根据品牌ID获取品牌名称
     *
     * @param $brandId
     *
     * @return mixed
     */
    public function getBrand($brandId)
    {
        return $this->getBrandMenu()[$brandId];
    }
    
    /**
     * 所有品牌 key => value
     *
     * @return array
     */
    public function getBrandMenu()
    {
        return ArrayHelper::map($this->getAllBrand(), 'car_brand_id', 'car_brand_name');
    }
    
    /**
     * 所有品牌
     *
     * @return array|CarBrandInfo[]|mixed|\yii\db\ActiveRecord[]
     */
    protected function getAllBrand()
    {
        $cache = \Yii::$app->cache;
        if($brand = $cache->get("ALL_CAR_BRAND")) {
            return $brand;
        }
        $brand = CarBrandInfo::find()->all();
        $cache->set("ALL_CAR_BRAND", $brand);
        return $brand;
    }
    
    /**
     * 根据厂商ID获取厂商名称
     *
     * @param $factoryId
     *
     * @return mixed
     */
    public function getFactory($factoryId)
    {
        $factory = $this->getFactoryMenu();
        return $factory[$factoryId];
    }
    
    /**
     * 所有厂商 key => value
     *
     * @return array
     */
    public function getFactoryMenu()
    {
        return ArrayHelper::map($this->getAllFactory(), 'factory_id', 'factory_name');
    }
    
    /**
     * 所有厂商
     *
     * @return array|CarBrandInfo[]|mixed|\yii\db\ActiveRecord[]
     */
    protected function getAllFactory()
    {
        $cache = \Yii::$app->cache;
        if($brand = $cache->get("ALL_CAR_FACTORY")) {
            return $brand;
        }
        $brand = CarFactoryInfo::find()->all();
        $cache->set("ALL_CAR_FACTORY", $brand);
        return $brand;
    }
    
    /**
     * 根据品牌ID获取厂商
     *
     * @param $brandId
     *
     * @return array|CarBrandFactoryInfo[]|\yii\db\ActiveRecord[]
     */
    public function geFactoryByBrandId($brandId)
    {
        $data = CarBrandFactoryInfo::find()->select([
            'a.factory_id  as key',
            'b.factory_name as value',
        ])->alias('a')->innerJoin(CarFactoryInfo::tableName(). ' b',
            "a.factory_id = b.factory_id" )->where([
            'a.brand_id' => $brandId
        ])->asArray()->all();
        return $data;
    }
    
    /**
     * 根据车系ID获取车系名称
     *
     * @param $seriesId
     *
     * @return mixed
     */
    public function getSeries($seriesId)
    {
        return $this->getSeriesMenu()[$seriesId];
    }
    
    /**
     * 所有车系 key => value
     *
     * @return array
     */
    public function getSeriesMenu()
    {
        return ArrayHelper::map($this->getAllSeries(), 'car_brand_type_id', 'car_brand_type_name');
    }
    
    /**
     * 所有车系
     *
     * @return array|CarBrandInfo[]|mixed|\yii\db\ActiveRecord[]
     */
    protected function getAllSeries()
    {
        $cache = \Yii::$app->cache;
        if($brand = $cache->get("ALL_CAR_SERIES")) {
            return $brand;
        }
        $brand = CarBrandTypeInfo::find()->all();
        $cache->set("ALL_CAR_SERIES", $brand);
        return $brand;
    }
    
    /**
     * 根据厂商ID得到车系
     *
     * @param $factoryId
     *
     * @return mixed
     */
    public function getSeriesByFactoryId($factoryId)
    {
        $series = CarBrandTypeInfo::find()->select([
            'car_brand_type_id  as key',
            'car_brand_type_name  as value',
        ])->where(['factory_id' => $factoryId])->asArray()->all();
        return $series ;
    }
    
    /**
     * 根据车型ID获取车型名称
     *
     * @param $carId
     *
     * @return mixed
     */
    public function getCar($carId)
    {
        return $this->getCarMenu()[$carId];
    }
    
    /**
     * 所有车系 key => value
     *
     * @return array
     */
    public function getCarMenu()
    {
        return ArrayHelper::map($this->getAllCar(), 'car_brand_son_type_id', 'car_brand_son_type_name');
    }
    
    /**
     * 所有车系
     *
     * @return array|CarBrandInfo[]|mixed|\yii\db\ActiveRecord[]
     */
    protected function getAllCar()
    {
        $cache = \Yii::$app->cache;
        if($brand = $cache->get("ALL_CAR")) {
            return $brand;
        }
        $brand = CarBrandSonTypeInfo::find()->all();
        $cache->set("ALL_CAR", $brand);
        return $brand;
    }
    
    /**
     * 根据车系得到车型
     *
     * @param $seriesId
     *
     * @return mixed
     */
    public function getCarBySeriesId($seriesId)
    {
        $cars = CarBrandSonTypeInfo::find()->select([
            'car_brand_son_type_id  as key',
            'car_brand_son_type_name  as value',
        ])->where(['car_brand_type_id' => $seriesId])->asArray()->all();;
        return $cars ;
    }
    
    /**
     * 根据车型获取内色、外色
     * 0 - 外色
     * 1 - 内色
     *
     * @param $carId
     *
     * @return string
     */
    public function getColorByCarId($carId)
    {
        $colors = CarBaseConfColor::find()->where(['car_id' => $carId])->all();
        $data =[];
        foreach ($colors as $color)
        {
            $data[$color->type][] = [
                'label' => $color->name,
                'value' => $color->id,
            ];
        }
        return json_encode($data);
    }
}