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
use yii\web\UploadedFile;

/**
 * 图片上传
 *
 * Class FileUpload
 * @package backend\controllers
 */
class FileUploadController extends BaseController
{
    public $enableCsrfValidation = false;
    /**
     * 上传图片到阿里云
     */
    public function actionIndex()
    {
        $this->returnJson($this->upload());
    }
    
    /**
     * 上传图片
     *
     * @return array|string
     */
    private function upload()
    {
        $file = UploadedFile::getInstanceByName('pics');
        $ext = $file->getExtension();
        $randName = md5($file->name) . "." . $ext;
        $returnUrl = OssLogic::instance()->uploadImgToOss($file->tempName, 'wangdiao/goods/'.$randName);
        if (!$returnUrl) {
            return '';
        }
        return ['url' => $returnUrl, 'name' => $randName];
    }
    
    /**
     * 删除图片
     */
    public function actionDelete($url)
    {
        $result = OssLogic::instance()->deleteOssFile($url);
        return $result;
    }
    
    /**
     * spu图片上传
     *
     * @param $id
     */
    public function actionItemImage($id)
    {
        $skuItem = SkuItem::findOne($id);
        if($skuItem){
            $url = $this->upload();
            if($url){
                $model = new SkuItemAttachment();
                $model->item_id = $id;
                $model->url = $url['url'];
                $model->partner_id = $skuItem->partner_id;
                $model->spu_id = $skuItem->spu_id;
                $model->type = 'image';
                $model->create_person = 'system';
                $model->status = 1;
                if($model->save()){
                    //$images = SkuItemAttachment::find()->select('url')->where(['item_id'=>$id])->asArray()->all();
                    $this->returnJson($this->upload());
                }
            }
        }
        $this->returnJson('上传失败', 0);
    }
    
    /**
     * 删除图片
     *
     * @param $url
     * @param $id
     *
     * @return string
     */
    public function actionDelItemImage($url,$id)
    {
        $skuItem = SkuItem::findOne($id);
        if($skuItem) {
            $attach = SkuItemAttachment::findOne(['url'=>$url,'item_id'=>$id]);
            $attach && $attach->delete();
            return $this->actionDelete($url);
        }else{
            return '商品不存在';
        }
    }
}