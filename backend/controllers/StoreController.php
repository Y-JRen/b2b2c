<?php

namespace backend\controllers;

use common\helpers\Helper;
use common\logic\StoreLogic;
use common\models\SkuItem;
use common\models\SkuItemStores;
use Yii;
use common\models\Store;
use common\logic\AMapLogic;
use yii\web\NotFoundHttpException;
use common\logic\PartnerLogic;
use yii\filters\VerbFilter;

/**
 * StoreController implements the CRUD actions for Store model.
 * 门店相关的一些相关的逻辑
 */
class StoreController extends Controller
{

    /**
     * @var string 定义modelClass
     */
    public $modelClass = 'common\models\Store';

    /**
     * 定义where 查询条件
     * @param array $params
     * @return array
     */
    public function where($params)
    {
        return [
            'partner_id' => '='
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Store models.
     * @return mixed
     */
    public function actionIndex()
    {
        // 查询合作商信息
        $partner = PartnerLogic::instance()->getPartnerMenu();

        return $this->render('index', [
            'partner' => $partner,
        ]);
    }

    /**
     * Displays a single Store model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Store model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Store the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Store::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 获取地址经纬度信息
     */
    public function actionGetAddress()
    {
        // 地址信息
        $strAddress = Yii::$app->request->get('address');
        if ($strAddress) {
            $result = AMapLogic::instance()->getAddress($strAddress);
            $this->arrJson['errMsg'] = '获取失败';
            if ($result) {
                $this->handleJson($result, 0, '获取成功');
            }
        }

        $this->asJson($this->arrJson);
    }

    public function actionStore()
    {
        return $this->render('_store', [
            'model' => SkuItem::findOne(13),
        ]);
    }

    /**
     * 获取spu item 的门店信息
     *
     * @return \yii\web\Response
     */
    public function actionGetStore()
    {
        // 实例化数据显示类
        $request = \Yii::$app->request;

        // 接收参数
//        $params = $request->post('params');  // 查询参数
        $intStart   = (int)$request->post('iDisplayStart',  0);   // 开始位置
        $intLength  = (int)$request->post('iDisplayLength', 10);  // 查询长度

        // 接收处理排序信息
        $sort  = $request->post('sSortDir_0', 'desc'); // 排序类型
        $field = 'create_time';
        $orderBy = [$field => $sort == 'asc' ? SORT_ASC : SORT_DESC];
//        $where = Helper::handleWhere($params, $this->where($params));

        // 查询数据
        $query = SkuItemStores::find()->with('store')->where(['item_id' => $request->get('id')]);
        if (YII_DEBUG) $this->arrJson['other'] = $query->createCommand()->getRawSql();

        // 查询数据条数
        $total = $query->count();
        if ($total) {
            $array = $query->offset($intStart)->limit($intLength)->orderBy($orderBy)->asArray()->all();
            if ($array) {
                $arrResult = [];
                foreach ($array as $key => $value) {
                    $arrResult[$key] = $value['store'];
                    $arrResult[$key]['id'] = $value['id'];
                }

                $array = $arrResult;
            };
        } else {
            $array = [];
        }

        // 处理返回数据
        $this->handleJson([
            'sEcho' => (int)$request->post('sEcho'), // 请求次数
            'iTotalRecords' => count($array),        // 当前页条数
            'iTotalDisplayRecords' => (int)$total,  // 数据总条数
            'aaData' => $array,                      // 数据信息
        ]);

        // 返回JSON数据
        return $this->asJson($this->arrJson);
    }

    /**
     * 获取spu item 可用的门店信息
     *
     * @return string
     */
    public function actionGetSelectStore()
    {
        $request = Yii::$app->request;
        $id = (int)$request->get('id');
        $intPartner = (int)$request->get('partner_id');
        $strAddress = $request->get('strAddressId');
        if ($id && $intPartner) {
            // 查询合作商门店信息
            $store = StoreLogic::instance()->findCanChooseSpuItemStore($id, $intPartner, $strAddress);
        } else {
            $store = [];
        }

        // 载入视图
        return $this->renderAjax('/partner/_select_store', [
            'title' => '添加门店',
            'label' => '选择门店',
            'store' => $store,
        ]);
    }

    /**
     * 处理spu item 门店修改
     *
     * @return \yii\web\Response
     */
    public function actionUpdateStore()
    {
        // 接受参数
        $id = Yii::$app->request->post('id');
        $intStoreId = Yii::$app->request->post('store_id');
        if ($id && $intStoreId) {
            if (StoreLogic::instance()->updateSpuItemStore($id, $intStoreId)) {
                $this->handleJson($id, 0, '添加成功');
            } else {
                $this->arrJson['errMsg'] = '添加失败';
            }
        }

        return $this->asJson($this->arrJson);
    }

    /**
     * 删除 spu item 门店信息
     *
     * @return \yii\web\Response
     */
    public function actionDeleteSpuItemStore()
    {
        $id = Yii::$app->request->post('id');
        if ($id) {
            if (StoreLogic::instance()->deleteSpuItemStore($id)) {
                $this->handleJson($id, 0, '取消选择成功');
            } else {
                $this->arrJson['errMsg'] = '取消选择失败';
            }
        }

        return $this->asJson($this->arrJson);
    }
}
