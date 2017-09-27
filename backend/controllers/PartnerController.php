<?php

namespace backend\controllers;

use backend\models\form\PartnerForm;
use common\logic\PartnerBaseIdentityLogic;
use common\logic\StoreLogic;
use Yii;
use common\models\Partner;
use backend\models\search\Partner as PartnerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use common\models\Store;
use common\traits\Json;

/**
 * PartnerController implements the CRUD actions for Partner model.
 */
class PartnerController extends Controller
{
    use Json;

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
     * Lists all Partner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PartnerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Partner model.
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
     * Creates a new Partner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PartnerForm();

        if ($model->load(Yii::$app->request->post()) && $model->baseSave()) {
            return $this->redirect(['index']);
        } else {
            if ($model->errors) {
                foreach ($model->errors as $error){
                    Yii::$app->session->setFlash('error', $error[0]);
                }
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Partner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new PartnerForm();
        $model = $model->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->baseSave()) {
            return $this->redirect(['index']);
        } else {
            if ($model->errors) {
                foreach ($model->errors as $error){
                    Yii::$app->session->setFlash('error', $error[0]);
                }
            }

            // 查询基础的权限信息
            $arrMenu = PartnerBaseIdentityLogic::instance()->getMenu();

            return $this->render('update', [
                'model' => $model,
                'menus' => $arrMenu,
            ]);
        }
    }

    /**
     *
     */
    public function actionDealer()
    {
        return $this->render('_dealer', [
            'model' => $model = new PartnerForm(),
        ]);
    }

    /**
     * Deletes an existing Partner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Partner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Partner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PartnerForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 获取合作商可用门店
     */
    public function actionGetStore()
    {
        // 合做商ID
        $id = (int)Yii::$app->request->get('id');
        $array = [];
        if ($id) {
            $array = (new Query())->select([
                't.province_name', 't.city_name', 't.area_name',
                't.address', 't.contact_person', 't.contact_phone', 't.name'
            ])->from('partner_seller_store p')
                ->innerJoin('store t', '`t`.`id` = `p`.`store_id`')
                ->where(['p.partner_id' => $id, 't.status' => Store::STATUS_ACTIVE])
                ->all();
        }

        $this->asJson(['data' => $array]);
    }


    /**
     * 显示商铺可以用的门店信息
     *
     * @return string
     */
    public function actionGetSelectStore()
    {
        // 合做商ID
        $id = (int)Yii::$app->request->get('id');
        $store = [];
        if ($id) {
            // 查询对外的门店(不是自己的)
            $store = (new Query())->select(['t.id', 't.name'])
                ->from('store t')
                ->where([
                    'and',
                    ['not in', 't.id', (new Query())->from('partner_seller_store')->select('store_id')->where(['partner_id' => $id])],
                    ['t.status' => Store::STATUS_ACTIVE],
                    ['t.foreign_service' => Store::FOREIGN_SERVICE_OPEN]
                ])
                ->all();
        }

        return $this->renderAjax('_select_store', [
            'store' => $store,
        ]);
    }

    /**
     * 商户添加门店，其他门店的
     *
     * @return \yii\web\Response
     */
    public function actionCreateStore()
    {
        $request = Yii::$app->request;
        $intStoreId = $request->post('store_id');
        $intPartnerId = $request->post('partner_id');
        if ($intStoreId && $intPartnerId) {
            // 查询门店是否存在
            $store = Store::findOne($intStoreId);
            $this->arrJson['errMsg'] = '门店信息不存在';
            if ($store && $store->partner_id != $intPartnerId) {
                if (StoreLogic::instance()->createStorePartner($intStoreId, $intPartnerId, 0)) {
                    $this->handleJson($store, 0, '添加成功');
                } else {
                    $this->arrJson['errMsg'] = '添加失败';
                }
            }
        }

        return $this->asJson($this->arrJson);
    }
}
