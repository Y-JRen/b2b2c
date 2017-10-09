<?php

namespace frontend\modules\web\controllers;

use common\logic\CarLogic;
use common\models\CarBrandInfo;
use frontend\controllers\BaseController;
use Yii;
use yii\db\Query;
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
        $intBrandId = (int)ArrayHelper::getValue($this->privateParam, 'car_brand_id');
        // 默认车系
        if ($intBrandId === 0) {
            $array = [];
        // 指定品牌车系
        } else {
            $array = CarLogic::instance()->getSeriesByBrandId($intBrandId);
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
        // 默认查询条件
        $where = [];

        // 品牌
        $brandId = (int)ArrayHelper::getValue($this->privateParam, 'brand_id');
        if ($brandId) {
            $where[] = ['=', 'c.brand_id', $brandId];
        }

        // 车系
        $intSeriesId = (int)ArrayHelper::getValue($this->privateParam, 'series_id');
        if ($intSeriesId) {
            $where[] = ['=', 'c.series_id', $intSeriesId];
        }

        // 首付金额(价格区间) - 传入的单位为万，库里面存储的单位为分 需要* 1000000
        $minPrice = ArrayHelper::getValue($this->privateParam, 'min_price');
        if ($minPrice) {
            $where[] = ['>', 'i.down_payment', $minPrice * 1000000];
        }

        $maxPrice = ArrayHelper::getValue($this->privateParam, 'max_price');
        if ($maxPrice) {
            $where[] = ['<=', 'i.down_payment', $maxPrice * 1000000];
        }

        if ($where) array_unshift($where, 'and');

        // 查询数据
        $query = (new Query())->select([
            'c.brand_id', 'c.brand_name', 'c.series_id',
            'c.series_name', 'c.car_type_id', 'c.car_type_name',
            'c.spu_id', 'i.partner_id', 'c.id', 'i.image'
        ])
            ->from('sku_spu_car as c')
            ->innerJoin('sku_item as i', '`c`.`spu_id` = `i`.`spu_id`')
            ->where($where);

        $total = (int)$query->count();
        $pages = $this->getPageParams();
        $lists = $query->offset($pages['offset'])->limit($pages['limit'])->all();

        if ($lists) {
            foreach ($lists as &$value) {
                $value['brand_id'] = (int)$value['brand_id'];
                $value['series_id'] = (int)$value['series_id'];
                $value['car_type_id'] = (int)$value['car_type_id'];
                $value['spu_id'] = (int)$value['spu_id'];
                $value['partner_id'] = (int)$value['partner_id'];
                $value['id'] = (int)$value['id'];
            }

            unset($value);
        }

        // 格式化后返回
        $array = $this->formatPageLists($lists, $pages['page'], $pages['size'], $total);
        $this->handleJson($array);
        return $this->returnJson();
    }
}