<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/26
 * Time: 17:58
 */

namespace backend\controllers;


use common\logic\OssLogic;
use common\models\SkuItem;
use common\models\SkuItemAttachment;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * 图片上传
 *
 * Class FileUpload
 * @package backend\controllers
 */
class FileUploadController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * 上传图片到阿里云
     */
    public function actionIndex()
    {
        $file = UploadedFile::getInstanceByName('pics');
        $returnUrl = OssLogic::instance()->uploadImgToOss($file->tempName, 'wangdiao/goods/'.$file->name);
        if (!$returnUrl) {
            return '';
        }
        return $returnUrl;
    }
    
    /**
     * 删除图片
     */
    public function actionDelete($url)
    {
        $result = OssLogic::instance()->deleteOssFile($url);
        return $result;
    }

    public function actionItemImage($id)
    {
        $skuItem = SkuItem::findOne($id);
        if($skuItem){
            $url = $this->actionIndex();
            if($url){
                $model = new SkuItemAttachment();
                $model->item_id = $id;
                $model->url = $url;
                $model->partner_id = $skuItem->partner_id;
                $model->spu_id = $skuItem->spu_id;
                $model->type = 'image';
                $model->create_person = 'system';
                $model->status = 1;
                if($model->save()){
                    //$images = SkuItemAttachment::find()->select('url')->where(['item_id'=>$id])->asArray()->all();
                    return $url;
                }
            }
        }
        return '';
    }

    public function actionDelItemImage($url,$id)
    {
        $skuItem = SkuItem::findOne($id);
        if($skuItem) {
            SkuItemAttachment::findOne(['url'=>$url,'item_id'=>$id])->delete();
            return $this->actionDelete($url);
        }else{
            return '商品不存在';
        }
    }
}