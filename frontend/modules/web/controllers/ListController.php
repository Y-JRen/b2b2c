<?php

namespace frontend\modules\web\controllers;

use common\helpers\Helper;
use common\logic\CarLogic;
use common\logic\SkuItemLogic;
use common\models\CarBrandInfo;
use frontend\controllers\BaseController;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ListsController
 * 列表页处理控制器
 * @package frontend\modules\web\controllers
 */
class ListController extends BaseController
{
    /**
     * 搜索信息[品牌信息、默认品牌、车系信息、默认车系信息、首付金额]
     *
     * @return mixed|string
     */
    public function actionSearchParams()
    {
        // 第一步获取默认的品牌信息
        $defaultBrands = [];

        // 第二步、全部的品牌信息
        $array = CarLogic::instance()->getAllBrand();

        // 处理返回类型
        if ($array) {
            $arrReturn = [];
            foreach ($array as $value) {
                $arrReturn[] = [
                    'car_brand_id' => (int)$value['car_brand_id'],
                    'car_brand_name' => $value['car_brand_name'],
                ];
            }

            $array = $arrReturn;
        }

        // 第三步、获取默认车系信息
        $defaultSeries = [];

        // 第三步、获取首付区间
        $downPayments = Yii::$app->params['arrDownPayment'];

        $this->handleJson([
            'defaultBrands' => $defaultBrands,
            'brands' => $array,
            'defaultSeries' => $defaultSeries,
            'downPayments' => $downPayments,
        ]);
        return $this->returnJson();
    }

    /**
     * 车系信息
     *
     * @return mixed|string
     */
    public function actionSeries()
    {
        // 品牌ID
        $strBrandId = ArrayHelper::getValue($this->privateParam, 'car_brand_id');
        // 默认车系
        $array = CarLogic::instance()->getSeriesByBrandId($strBrandId);
        // 处理返回类型
        if ($array) {
            $arrReturn = [];
            foreach ($array as $value) {
                $arrReturn[] = [
                    'car_brand_type_id' => (int)$value['car_brand_type_id'],
                    'car_brand_type_name' => $value['car_brand_type_name']
                ];
            }

            $array = $arrReturn;
        }

        $this->handleJson($array);

        return $this->returnJson();
    }

    /**
     * 商品spu 列表
     *
     * @return mixed|string
     */
    public function actionSearch()
    {
        // 查询条件
        $where = Helper::handleWhere($this->privateParam, [
            // 品牌
            'brand_id' => ['field' => 'c.brand_id'],
            // 车系
            'series_id' => ['field' => 'c.series_id'],
            // 最小首付金额价格
            'min_price' => function($value) {
                return ['>', 'i.down_payment', $value * 1000000];
            },
            // 最大首付金额价格
            'max_price' => function($value) {
                return ['<=', 'i.down_payment', $value * 1000000];
            }
        ]);

        // 查询数据
        $pages = $this->getPageParams();
        $arrResult = SkuItemLogic::instance()->searchItem([
            'where' => $where,
            'offset' => $pages['offset'],
            'limit' => $pages['limit'],
        ]);

        // 格式化后返回
        $array = $this->formatPageLists($arrResult['lists'], $pages['page'], $pages['size'], $arrResult['total']);
        return $this->returnJson([
            'data' => $array,
            'errCode' => 0
        ]);
    }
}