<?php
namespace frontend\modules\web\controllers;

use common\helpers\Helper;
use common\logic\SkuItemLogic;
use frontend\controllers\BaseController;
use frontend\logic\PartnerLogic;

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
            // 处理查询条件
            $where = Helper::handleWhere($this->privateParam, [
                // 默认条件
                'where' => [
                    ['=', 'i.partner_id', (int)$this->privateParam['partner_id']]
                ],

                // 品牌
                'brand_id' => ['field' => 'c.brand_id'],
                // 车系
                'series_id' => ['field' => 'c.series_id'],

                // 最低价格
                'min_price' => function($value) {
                    return ['>', '', $value * 1000000];
                },

                // 最大价格
                'max_price' => function($value) {
                    return ['<=', '', $value * 1000000];
                }
            ]);

            // 查询数据
            $pages = $this->getPageParams();
            $arrResult = SkuItemLogic::instance()->searchItem([
                'where' => $where,
                'limit' => $pages['limit'],
                'offset' => $pages['offset']
            ]);

            // 格式化后返回
            $array = $this->formatPageLists($arrResult['lists'], $pages['page'], $pages['size'], $arrResult['total']);
            $this->handleJson($array);
        } else {
            $this->arrJson['errCode'] = 1004;
        }

        return $this->returnJson();
    }
}

