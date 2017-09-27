<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/25
 * Time: 21:21
 */

namespace common\client;


use common\client\api\AMapClient;
use yii\helpers\ArrayHelper;

class BMap
{
    public static function getAddress($strAddress, $key = 'location')
    {
        // 请求接口
        $mixResult = AMapClient::instance()->getCodeGeo($strAddress);

        // 判断请求成功
        if ($mixResult && !empty($mixResult['status']) && $mixResult['status'] == 1) {
            $mixReturn = array_shift($mixResult['geocodes']);
            $mixReturn = ArrayHelper::getValue($mixReturn, $key);
            if ($mixReturn && $key === 'location') {
                $mixReturn = explode(',', $mixReturn);
            }
        } else {
            $mixReturn = null;
        }

        return $mixReturn;
    }
}