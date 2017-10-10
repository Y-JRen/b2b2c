<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\Cors;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use common\logic\UcenterLogic;
use common\traits\Redis;


/**
 * Class BaseController
 *  基础的控制器,所有的api 控制器必须基础该基础控制器
 *  其中的publicParam 和 privateParam 公共参数和私有参数，不能再子类控制器中修改
 *
 * @package frontend\controllers
 *
 * @property array $publicParam 公共请求的参数
 * @property array $privateParam 接口需要的请求参数
 */
class BaseController extends Controller
{
    /**
     * 使用缓存功能
     */
    use Redis;

    /**
     * 使用json 返回数据功能
     */
    use \common\traits\Json;

    /**
     * @var bool 关闭csrf 验证
     */
    public $enableCsrfValidation = false;

    /**
     * @var array 定义公共的参数（基本参数）
     */
    protected $publicParam = [
        'versionCode' => 1,     // 版本号 code
        'versionName' => '',    // 版本名称
        'platform' => '',       // 平台 - pc m站  android  ios 等
        'token' => '',          // 登录token - 未登录的话 传空字符串
        'uid' => '',            // 用户uid
    ];

    /**
     * @var array 定义私有参数，接口传递参数
     */
    protected $privateParam = [];

    /**
     * @return array 定义行为
     */
    public function behaviors()
    {
        return [
            // 允许跨域
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['POST', 'GET'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                ],
            ],

            // 所有请求必须使用POST 请求
        ];
    }

    /**
     * 所有请求控制器之前的请求操作
     *
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $this->initInputParam();
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * 初始化请求参数
     */
    private function initInputParam()
    {
        $request = Yii::$app->request;
        $strPublic = $request->post('publicParam');
        $strPrivate = $request->post('privateParam');
        $this->publicParam = array_merge($this->publicParam, (array)Json::decode($strPublic));
        $this->privateParam = (array)Json::decode($strPrivate);
    }

    /**
     * 验证是否已经登陆
     *
     * @return bool
     */
    protected function checkLogin()
    {
        $isReturn = false;
        $uid = ArrayHelper::getValue($this->publicParam, 'uid');
        $token = ArrayHelper::getValue($this->publicParam, 'token');
        if ($uid && $token) {
            // 拼接key
            $key = 'uid:' . $uid . ':token:' . $token;

            // "token": "be025c89d4c03acd7046c925e25bd337",
            // "uid": 89220
            $userInfo = $this->getCache($key);

            if (!$userInfo) {
                // 缓存无效 去用户中心查询
                if (!UcenterLogic::instance()->checkLogin($uid, $token)) {

                } else {
                    $userInfo = [];
                    // 缓存有效时间10分钟
                    $this->setCacheTime($key, $userInfo, Yii::$app->params['apiUserTokenTime']);
                }
            } else {
                $isReturn = true;
            }
        }

        return $isReturn;
    }

    /**
     * 获取分页请求的数据，和计算分页查询
     *
     * @return array 返回分页查询数据信息
     * ```
     * page 当前页
     * size 每页条数
     * offset 起始查询位置
     * limit 查询条数
     * ```
     */
    protected function getPageParams()
    {
        // 分页信息
        $intPage = max(intval(ArrayHelper::getValue($this->privateParam, 'page')), 1);
        $intSize = ArrayHelper::getValue($this->privateParam, 'page_size');
        $intPageSize = $intSize > 0 ? $intSize : Yii::$app->params['pageSize'];

        // 返回数据信息
        return [
            'page' => $intPage,
            'size' => $intPageSize,
            'offset' => ($intPage - 1) * $intPageSize,
            'limit' => $intPageSize
        ];
    }

    /**
     * 格式分页参数
     *
     * @param integer $intPage 第几页
     * @param int $intPageSize 每页多少条
     * @param int $intTotal 数据总条数
     * @return array
     */
    protected function formatPages($intPage, $intPageSize, $intTotal)
    {
        return [
            'currentPage' => intval($intPage),
            'pageSize' => intval($intPageSize),
            'totalCount' => intval($intTotal),
        ];
    }

    /**
     * 格式化分页数据
     *
     * @param array $array 数据信息
     * @param int $intPage 当前页
     * @param int $intPageSize 每页条数
     * @param int $intTotal 总数据条数
     * @return array
     */
    protected function formatPageLists($array, $intPage, $intPageSize, $intTotal)
    {
        return [
            'pages' => $this->formatPages($intPage, $intPageSize, $intTotal),
            'lists' => $array
        ];
    }
}

