<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/26
 * Time: 17:58
 */

namespace backend\controllers;


use common\logic\OssLogic;
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
}