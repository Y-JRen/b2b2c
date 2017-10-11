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
     * 上传图片张数,单位 KB
     * @var int
     */
    public $maxSize = 1024;
    
    /**
     * 上传链接
     *
     * @var string
     */
    public $uploadUrl;
    
    
    public $limit = 1;
    
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
        $this->deleteUrl = strpos($this->deleteUrl,'?') > 0 ? $this->deleteUrl : $this->deleteUrl.'?';
    }
    
    /**
     * @return string
     */
    public function run()
    {
        $this->registerClientScript();
        $content = Html::beginTag('div', ['id' => 'vueUpload']);
        $content .= Html::beginTag('div', ['id' => $this->attribute.'_vueUpload']);
        $key = $this->attribute;
        if ($this->multiple) {
            foreach ($this->model->$key as $k => $v){
                $content .= Html::hiddenInput(Html::getInputName($this->model, $this->attribute).'['.$k.']',
                    $v, ['class' => $this->attribute]);
            }
        } else {
            $content .= Html::hiddenInput(Html::getInputName($this->model, $this->attribute),
                $this->model->$key, ['id' => $this->attribute]);
        }
    
        $content .= Html::endTag('div');
        $content .= ' <el-upload
  class="upload"
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
        $name = Html::getInputName($this->model, $this->attribute);
        VueAsset::register($this->getView());
        if($this->multiple) {
            $key = $this->attribute;
            $fileList = [];
            if($this->model->$key) {
                foreach ($this->model->$key as $v){
                    $pathInfo = pathinfo($v);
                    $fileList[] = [
                        'name' => $pathInfo['basename'],
                        'url' => $v
                    ];
                }
            }
            $count = count($fileList) ? : 0;
            $fileList = json_encode($fileList);
        } else {
            $key = $this->attribute;
            $fileList = [];
            if($this->model->$key) {
                $pathInfo = pathinfo($this->model->$key);
                $fileList[] = [
                    'name' => $pathInfo['basename'],
                    'url' => $this->model->$key
                ];
            }
            $count = count($fileList) ? : 0;
            $fileList = json_encode($fileList);
        }
        $errorMessage = '$message';
        $hiddenId = $this->attribute.'_vueUpload';
        $js = <<<__SCRIPT
var vue = new Vue({
    el: '#vueUpload',
    data: function () {
        return {
            fileList: {$fileList},
            count: {$count}
        };
    },
    methods: {
        handleRemove(file, fileList) {
            $.get('{$this->deleteUrl}&url='+file.response)
            this.count--
            var html = '';
            console.log(fileList)
            for(var key in  fileList){
                html += '<input type="hidden" name="{$name}['+key+']" value="'+ fileList[key].url +'">';
            }
            $('#{$hiddenId}').html(html)
        },
        handleSuccess(response, file, fileList) {
            this.count++
            var html = '';
            console.log(fileList.length)
            for(var key in  fileList){
                console.log(fileList)
                html += '<input type="hidden" name="{$name}" value="'+fileList[key].response+'">';
            }
            $('#{$hiddenId}').html(html)
        },
        beforeAvatarUpload(file) {
            const maxSize = file.size / 1024  < {$this->maxSize};
            if(this.count == {$this->limit}){
                this.{$errorMessage}.error('最多上传{$this->limit}张图片!');
                return false;
            }
            if (!maxSize) {
              this.{$errorMessage}.error('上传头像图片大小不能超过 {$this->maxSize} KB!');
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