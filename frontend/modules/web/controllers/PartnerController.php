<?php
namespace frontend\modules\web\controllers;

use frontend\controllers\BaseController;
use frontend\logic\PartnerLogic;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class PartnerController
 * 合作商处理控制器
 * @package frontend\controllers
 */
class PartnerController extends BaseController
{
    /**
     * 厂商基础信息
     *
     * @return mixed|string
     */
    public function actionIndex()
    {
        // 验证请求参数(厂商ID不能为空)
        if (!empty($this->privateParam['partner_id'])) {
            // 查询厂商基本信息
            $array = PartnerLogic::instance()->getPartnerInfo((int)$this->privateParam['partner_id']);
            if ($array) {
                $this->handleJson($array);
            } else {
                // 厂商信息不存在
                $this->arrJson['errCode'] = 2001;
            }
        } else {
            $this->arrJson['errCode'] = 1004;
        }

        return $this->returnJson();
    }

    /**
     * 获取厂商的品牌信息
     *
     * @return mixed|string
     */
    public function actionBrands()
    {
        // 验证请求参数(厂商ID不能为空)
        if (!empty($this->privateParam['partner_id'])) {
            $array = PartnerLogic::instance()->getPartnerBrands((int)$this->privateParam['partner_id']);
            $this->handleJson($array);
        } else {
            $this->arrJson['errCode'] = 1004;
        }

        return $this->returnJson();
    }

    /**
     * 获取厂商的价格区间
     *
     * @return mixed|string
     */
    public function actionPriceInterval()
    {
        $array = PartnerLogic::instance()->getPriceInterVal();
        $this->handleJson($array);
        return $this->returnJson();
    }

    /**
     * 获取合作商的spu
     *
     * @return mixed|string
     */
    public function actionSearch()
    {
        // 验证请求参数(厂商ID不能为空)
        if (!empty($this->privateParam['partner_id'])) {
            // 默认查询条件
            $where = [
                'and',
                ['=', 'i.partner_id', (int)$this->privateParam['partner_id']]
            ];

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

            // 价格区间 - 传入的单位为万，库里面存储的单位为分 需要* 1000000
            $minPrice = ArrayHelper::getValue($this->privateParam, 'min_price');
            if ($minPrice) {
                $where[] = ['>', 'guide_price', $minPrice * 1000000];
            }

            $maxPrice = ArrayHelper::getValue($this->privateParam, 'max_price');
            if ($maxPrice) {
                $where[] = ['<=', 'guide_price', $maxPrice * 1000000];
            }

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

        } else {
            $this->arrJson['errCode'] = 1004;
        }

        return $this->returnJson();
    }
}

