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
     * 品牌信息
     *
     * @return mixed|string
     */
    public function actionBrands()
    {
        // 类型
        $intType = (int)ArrayHelper::getValue($this->privateParam, 'type');

        // 默认品牌
        if ($intType === 0) {
            $array = [];
        // 全部品牌
        } else {
            $array = CarBrandInfo::find()->select(['car_brand_id', 'car_brand_name'])
                ->where(['is_used' => CarBrandInfo::IS_USED_YES])
                ->asArray()
                ->all();
        }

        // 处理返回类型
        if ($array) {
            foreach ($array as &$value) {
                $value['car_brand_id'] = (int)$value['car_brand_id'];
            }

            unset($value);
        }

        $this->handleJson($array);
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
        if ($strBrandId == 0) {
            $array = [];
        // 指定品牌车系
        } else {
            $array = CarLogic::instance()->getSeriesByBrandId($strBrandId);
        }

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
     * 首付金额
     *
     * @return mixed|string
     */
    public function actionDownPayment()
    {
        $this->handleJson(Yii::$app->params['arrDownPayment']);
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