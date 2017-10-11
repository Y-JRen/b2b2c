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
     * 验证用户是否已经登陆
     *
     * @param integer $uid 用户ID
     * @param string $token 用户token
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
        $arrResult = HttpLogic::http_post($url, $post, true);
        // 成功失败
        return (isset($arrResult['err_code']) && $arrResult['err_code'] == 0);
    }

    /**
     * 通过token 获取用户的基本信息
     * @param string $token 用户登录授权的token
     * @return null|array
     */
    public function getUserInfo($token)
    {
        $url = $this->cfg['domain'].'/sso/user-base/info';
        $arrResult = HttpLogic::http_get($url, ['token' => $token],true);

        // 验证请求是否成功
        if (isset($arrResult['err_code']) && $arrResult['err_code'] == 0) {
            $mixReturn = $arrResult['data'];
        } else {
            $mixReturn = null;
        }

        return $mixReturn;
    }

}
