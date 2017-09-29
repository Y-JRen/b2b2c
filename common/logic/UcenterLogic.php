<?php
/**
 * 与用户/支付中心交互的逻辑层 包括 用户基本信息和支付相关功能的对接.
 * User: 雕
 * Date: 2017/9/15
 * Time: 16:57
 */
namespace common\logic;

use Yii;
use yii\helpers\ArrayHelper;


class UcenterLogic extends Instance
{
    private $cfg = [];

    public function __construct()
    {
        $this->cfg = ArrayHelper::getValue(Yii::$app->params, 'userCenter');
        if (empty($this->cfg)) {
            throw new \Exception('配置信息错误');
        }
    }

    /**
     * @param $uid
     * @param $token
     * @return bool
     */
    public function checkLogin($uid, $token)
    {
        $post = [
            'uid' => $uid,
            'domain' => ArrayHelper::getValue($this->cfg, 'selfProject'),
            'token' => $token,
            'tokenApi' => ArrayHelper::getValue($this->cfg, 'tokenApi'),
        ];
        $url = $this->cfg['domain'] . '/sso/user/check-login';
        $jsonResult = HttpLogic::http_post($url, $post, [], true);
        $arrResult = json_decode($jsonResult, true);
        return (isset($arrResult['err_code']) && $arrResult['err_code'] == 0);//成功 失败
    }

}
