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
    public $maxSize = 1;
    
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
  class="upload-demo"
  action="'.$this->uploadUrl.'"
  name="pics"
  :on-success="handleSuccess"
  :on-remove="handleRemove"
  :before-upload="beforeAvatarUpload"
  :file-list="fileList"
  :multiple=false
  list-type="picture">
  <el-button size="small" type="primary">点击上传</el-button>
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
        $errorMessage = '$message';
        $js = <<<__SCRIPT
vue = new Vue({
    el: '#vueUpload',
    data: function () {
        return {
            fileList: [],
            count: 0
        };
    },
    methods: {
        handleRemove(file, fileList) {
            console.log(file, fileList);
            {$changeValue}.val('')
            $.get('{$this->deleteUrl}?url='+file.response)
            this.count--
        },
        handleSuccess(response, file, fileList) {
            {$changeValue}.val(response)
            this.count++
        },
        beforeAvatarUpload(file) {
            if(this.count == {$this->maxSize}){
                this.{$errorMessage}.error('最多上传一张图片!');
                return false;
            }
        }
    }
})

__SCRIPT;
        $css = <<<_css
input.el-upload__input{display:none}
_css;

    
        $this->getView()->registerJs($js);
        $this->getView()->registerCss($css);
    }
}