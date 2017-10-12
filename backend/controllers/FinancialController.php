<?php

namespace backend\controllers;

use common\client\BMap;
use Yii;
use common\models\FinancialBaseDownPaymentRatio;
use common\models\FinancialBaseRepamentPeriod;
use common\models\FinancialProgram;
use common\models\FinancialProgramInfo;
use common\models\FinancialServiceArea;
use common\models\SkuSkuFinancial;
use common\models\SkuSku;
use backend\models\search\Store as StoreSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
/**
 * StoreController implements the CRUD actions for Store model.
 */
class FinancialController extends Controller
{
    /**
     * @var array 返回json 数据
     */
    public $arrJson = [
        'errCode' => 1,
        'errMsg' => '请求参数为空',
        'data' => [],
    ];
	public $finType = ['1'=>'普通车贷'];
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
     * @return string
     */
    public function actionIndex()
    {
        $FinancialProgramModel = new FinancialProgram();
		$get = Yii::$app->request->get();
		
		$totalCount  = $FinancialProgramModel->findCount($get);
		//echo 111;exit;
        $pagination  = new Pagination(compact('totalCount'));
        $dataFinancialProgram = $FinancialProgramModel->search($get); 
		//获取所有的金融方案期数
		$periodsList    = FinancialBaseRepamentPeriod::find()->where([ '=', 'status', '1'  ]); 
		if($periodsList !==  null ){
            $info['periodsList'] = $periodsList->asArray()->all();
        }
		//获取所有的金融方案比例
		$proportionList = FinancialBaseDownPaymentRatio::find()->where([ '=', 'status', '1' ]); 
		if($proportionList !==  null ){
            $info['proportionList'] = $proportionList->asArray()->all();
        }
        return $this->render('index', [
            'list' 			 => $dataFinancialProgram,
			'pagination' 	 => $pagination,
			'periodsList'	 => $info['periodsList'],
			'proportionList' => $info['proportionList'],
			'finType'    	 => $this->finType
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
     * Creates a new Store model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $post	= Yii::$app->request->post();
        $id 	= ArrayHelper::getValue($post, 'id');
		$info['id']   		 	= '';
        $info['name'] 			= '';
        $info['type'] 			= '1';
        $info['des']      	 	= ''; 
		$info['no']      	 	= ''; 		
        $info['periodsList'] 	= []; 
		$info['proportionList'] = []; 
		$info['ProgramInfo'] 	= [];
		$info['finType'] 		= $this->finType;

        //获取所有的金融方案期数
        $periodsList    = FinancialBaseRepamentPeriod::find()->where([ '=', 'status', '1'  ]);
        $info['periodsList']['header'][0] = '首付\周期';
        if($periodsList !==  null ){
            foreach($periodsList->asArray()->all() as $key=>$value){
                $info['periodsList']['header'][ $value['id'] ] = $value['name'];
            }
        }
        //获取所有的金融方案比例
        $proportionList = FinancialBaseDownPaymentRatio::find()->where([ '=', 'status', '1' ]);

        $info['periodsList']['data'][] = [];
        if( $proportionList !==  null ){ 	
			foreach($proportionList->asArray()->all() as $k=>$v){ 
				$info['periodsList']['data'][$k][0] =  $v['name'].'(月利率%)'; 
				foreach ( $info['periodsList']['header'] as $qs=>$head ) { 
					if($qs>0){
						$info['periodsList']['data'][$k][$qs] = 0; 
					}
				} 
            }
        }  
        if ($model = FinancialProgram::findOne($id)) {
            $info['title']   = '编辑金融方案';
            $info['id']      = $model->id;
            $info['name']    = $model->name;
            $info['type']    = $model->type;
			$info['no']      = $model->no;
            $info['des']     = $model->des; 
            //获取金融方案比例值 
            $ProgramInfo = FinancialProgramInfo::find()->where( [ '=', 'financial_id', $id  ] );
            if($ProgramInfo !==  null ){
                $info['ProgramInfo'] = $ProgramInfo->asArray()->all();
				foreach ( $info['ProgramInfo'] as $key=>$val){
					$info['periodsList']['data'][$val['ratio_id']][$val['period_id']] = $val['rate'];
				}
            }
        } else {
            $info['title']   = '添加金融方案';
        }  
        return  json_encode($info);

    }

    /**
     * Updates an existing Store model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Store model.
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
            $result = BMap::getAddress($strAddress);
            $this->arrJson['errMsg'] = '获取失败';
            if ($result) {
                $this->handleJson($result, '获取成功');
            }
        }

        $this->asJson($this->arrJson);
    }

    public function handleJson($data, $message = '处理成功', $code = 0)
    {
        $this->arrJson['data'] = $data;
        $this->arrJson['errMsg'] = $message;
        $this->arrJson['errCode'] = $code;
    }
}
