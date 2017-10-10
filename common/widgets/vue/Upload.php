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
        $name = Html::getInputName($this->model, $this->attribute);
        if ($this->multiple) {
            $content .= '<input v-for="(item, index) in fileList" name="'.$name.'[${index}]" type="hidden" v-model="item.url">';
        } else {
            $content .= '<input v-for="item in fileList" name="'.$name.'" type="hidden" v-model="item.url">';
    
        }
        $content .= ' <el-upload
  class="upload"
  action="'.$this->uploadUrl.'"
  name="pics"
  :on-success="handleSuccess"
  :on-remove="handleRemove"
  :on-change="handleChange"
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
            //vue.fileList.splice(fileList.length)
            $.get('{$this->deleteUrl}&url='+file.response)
            this.count--
        },
        handleSuccess(response, file, fileList) {
            console.log(fileList)
            //vue.fileList.splice(fileList.length)
            this.count++
        },
        handleChange(file, fileList) {
            if(fileList.length > 1 ) {
                vue.fileList.splice(fileList.length)
            }
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