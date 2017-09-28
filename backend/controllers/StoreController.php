<?php

namespace backend\controllers;

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
}
