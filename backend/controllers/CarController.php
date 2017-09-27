<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/13
 * Time: 15:20
 */

namespace backend\controllers;


use common\logic\CarLogic;
use yii\web\Controller;

/**
 * 车相关接口
 *
 * Class CarController
 * @package backend\controllers
 */
class CarController extends Controller
{
    /**
     * 品牌获取厂商
     *
     * @param $brand_id
     *
     * @return string
     */
    public function actionBrand($brand_id)
    {
        $brand = CarLogic::instance()->geFactoryByBrandId($brand_id);
        return json_encode($brand);
    }
    
    /**
     * 厂商获取车系
     *
     * @param $factory_id
     *
     * @return string
     */
    public function actionSeries($factory_id)
    {
        $data = CarLogic::instance()->getSeriesByFactoryId($factory_id);
        return json_encode($data);
    }
    
    /**
     * 车系获取车型
     *
     * @param $car_type_id
     *
     * @return string
     */
    public function actionCar($car_type_id)
    {
        $data = CarLogic::instance()->getCarBySeriesId($car_type_id);
        return json_encode($data);
    }
    
    /**
     * @param $car_id
     *
     * @return string
     */
    public function actionColor($car_id)
    {
        $colors = CarLogic::instance()->getColorByCarId($car_id);
        return json_encode($colors);
    }
}