<?php

namespace backend\controllers;

use backend\models\form\SpuItemForm;
use common\logic\SkuLogic;
use common\models\SkuFinancialLease;
use common\models\SkuItemAttachment;
use common\models\SkuItemStores;
use common\models\Store;
use Yii;
use backend\models\form\SpuForm;
use backend\models\search\Spu;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SpuController implements the CRUD actions for SpuForm model.
 */
class SpuController extends Controller
{
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


    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',//TODO 继承重写（文件存阿里云）
            ]
        ];
    }

    /**
     * Lists all SpuForm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Spu();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SpuForm model.
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
     * Creates a new SpuForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SpuForm();

        if ($model->load(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['update', 'id' => $model->id]);
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
     * Updates an existing SpuForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = SpuItemForm::findOne($id);
        $fm = Yii::$app->request->post('fm');
        if($fm == 'introduce'){
            $model->setScenario($model::SCENARIO_SAVE_INTRODUCE);
        }else{
            $model->setScenario($model::SCENARIO_SAVE_BASE);
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveItem()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            if ($model->errors) {
                foreach ($model->errors as $error){
                    Yii::$app->session->setFlash('error', $error[0]);
                }
            }
            return $this->render('update', [
                'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing SpuForm model.
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
     * Finds the SpuForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SpuForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SpuForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 删除SKU
     *
     * @param $skuId
     * @param $id
     *
     * @return \yii\web\Response
     */
    public function actionDeleteSku($skuId, $id)
    {
        $rst = SkuLogic::instance()->deleteSku($skuId);
        if($rst) {
            Yii::$app->session->setFlash('success', '删除成功');
        } else {
            Yii::$app->session->setFlash('error', '删除失败');
        }
        return $this->redirect(['update', 'id' => $id]);
    }
    
    /**
     * 提车地点
     *
     * @param $id
     *
     * @return string
     */
    public function actionStore($id)
    {
        $query = Store::find()->alias('a')->innerJoin(SkuItemStores::tableName() .' as b',
            'b.store_id = a.id'
        )->andWhere([
            'b.item_id' => $id
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        return $this->renderAjax('store', ['dataProvider' => $dataProvider]);
    }
    
    /**
     * 融资租凭 金融方案
     *
     * @param $skuId
     *
     * @return string
     */
    public function actionFinancialLease($skuId)
    {
        $query = SkuFinancialLease::find()->where(['sku_id' => $skuId]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        return $this->renderAjax('lease', ['dataProvider' => $dataProvider]);
    }
}
