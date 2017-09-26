<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/26
 * Time: 17:39
 */

namespace common\widgets\vue;


use common\widgets\vue\asset\VueAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 *
 * Class Upload
 * @package common\widgets\vue
 */
class Upload extends InputWidget
{
    /**
     * 是否支持多选文件
     * @var  boolean
     */
    public $multiple = false;
    
    /**
     * 上传图片张数
     * @var int
     */
    public $maxSize;
    
    /**
     * 上传链接
     *
     * @var string
     */
    public $uploadUrl;
    
    /**
     * 删除链接
     *
     * @var string
     */
    public $deleteUrl;
    
    
    public function init()
    {
        parent::init();
        if(!$this->uploadUrl) {
            throw new InvalidConfigException('未指定上传图片');
        }
    }
    
    /**
     * @return string
     */
    public function run()
    {
        $this->registerClientScript();
        $content = Html::beginTag('div', ['class' => 'hidden']);
        $content .= Html::hiddenInput(Html::getInputName($this->model, $this->attribute), null,['id' => $this->attribute]);
        
        $content .= Html::endTag('div');
        $content .= Html::beginTag('div', ['id' => 'vueUpload']);
        $content .= ' <el-upload
  class="upload"
  action="'.$this->uploadUrl.'"
  name="pics"
  :on-success="handleSuccess"
  :on-remove="handleRemove"
  :file-list="fileList"
  :multiple=false
  list-type="picture">
  <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
</el-upload> ';
        $content .= Html::endTag('div');
        return $content;
    }
    
    
    /**
     * Registers required script for the plugin to work as jQuery File Uploader
     */
    public function registerClientScript()
    {
        VueAsset::register($this->getView());
        if($this->multiple) {
        
        } else {
            $changeValue = '$("#'.$this->attribute.'")';
        }
        $js = <<<__SCRIPT
new Vue({
    el: '#vueUpload',
    data: function () {
        return {
            fileList: []
        };
    },
    methods: {
        handleRemove(file, fileList) {
            console.log(file, fileList);
            {$changeValue}.val()
        },
        handleSuccess(response, file, fileList) {
            {$changeValue}.val(response)
        }
    }
})
__SCRIPT;
    
        $this->getView()->registerJs($js);
    }
}