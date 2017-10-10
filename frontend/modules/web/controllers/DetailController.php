<?php

namespace frontend\modules\web\controllers;

use common\logic\CarBaseConfLogic;
use common\logic\FinancialLogic;
use common\logic\SpuLogic;
use common\logic\StoreLogic;
use common\models\SkuSku;
use frontend\controllers\BaseController;
use yii\helpers\ArrayHelper;

/**
 * Class DetailController
 * 详情页面控制器信息
 * @package frontend\modules\web\controllers
 */
class DetailController extends BaseController
{
    /**
     * 商品信息
     *
     * @return mixed|string
     */
    public function actionIndex()
    {
        // sku id
        $id = ArrayHelper::getValue($this->privateParam, 'id');
        if ($id) {
            // 查询sku 信息
            $sku = SkuSku::find()->with('parameter')->where(['id' => $id])->asArray()->one();
            if ($sku) {
                // 格式化数据
                $sku['id'] = (int)$sku['id'];
                $sku['spu_id'] = (int)$sku['spu_id'];
                $sku['item_id'] = (int)$sku['item_id'];
                $sku['partner_id'] = (int)$sku['partner_id'];

                // 查询spu_id 的属性信息
                $arrParameter = SpuLogic::instance()->getParameterValueBySupId($sku['spu_id']);

                // 查询门店信息
                $arrStore = StoreLogic::instance()->getStoresBySkuId($sku['id'], $sku['item_id']);

                // 金融方案信息
                $arrFinancial = FinancialLogic::instance()->getFinancialBySkuId($sku['id'], $sku['item_id']);

                // 返回数据
                $this->handleJson([
                    'detail' => $sku,             // 基础信息
                    'parameter' => $arrParameter, // 属性信息
                    'store' => $arrStore,        // 门店信息
                    'financial' => $arrFinancial, // 金融方案
                ]);
            } else {
                $this->arrJson['errCode'] = 3003;
            }
        } else {
            $this->arrJson['errCode'] = 3002;
        }

        return $this->returnJson();
    }


    /**
     * 商品的金融方案信息
     *
     * @return mixed|string
     */
    public function actionFinancial()
    {
        return $this->returnJson();
    }

    /**
     * 车型详情信息
     *
     * @return mixed|string
     */
    public function actionDetail()
    {
        return $this->returnJson();
    }

    /**
     * 车型配置信息
     *
     * @return mixed|string
     */
    public function actionConfig()
    {
        // 获取类型
        $type = ArrayHelper::getValue($this->privateParam, 'type');
        $intCarId = ArrayHelper::getValue($this->privateParam, 'car_id');

        // 传递参数不能为空
        if ($type && $intCarId) {
            $array = CarBaseConfLogic::instance()->getConfig($type, $intCarId);
            $array['arrConfigDesc'] = CarBaseConfLogic::instance()->getConfigDesc();
            $this->handleJson($array);
        } else {
            $this->arrJson['errCode'] = 3001;
        }

        return $this->returnJson();
    }

    /**
     * 车型视频信息
     *
     * @return mixed|string
     */
    public function actionVideo()
    {
        return $this->returnJson();
    }
}