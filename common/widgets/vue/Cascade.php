<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/19
 * Time: 15:34
 */

namespace common\widgets\vue;

use common\widgets\vue\asset\VueAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * vue 级联
 *
 * Class Cascade
 * @package common\widgets
 */
class Cascade extends InputWidget
{
    /**
     * 字段
     *
     * @var array
     */
    public $attributes = [];


    public $clientOptions = [];

    public $cascadeData = [];

    public $callback = '';

    /**
     * @var string vue 定义js 变量
     */
    public $jsVariableName = '';

    /**
     * @var string 默认值
     */
    public $defaultValue = '';


    public function init()
    {
        parent::init();
        if(empty($this->attributes) || count($this->attributes) < 2) {
            throw new InvalidConfigException('级联字段未指定');
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $content = Html::beginTag('div', ['class' => 'hidden']);
        $strAddress = '';
        foreach ($this->attributes as $attribute) {
            $strAddress .= '"'.$this->model->$attribute.'",';
            $content .= Html::hiddenInput(Html::getInputName($this->model, $attribute), $this->model->$attribute, ['id' => $attribute]);
        }

        // 处理默认选中
        if (!$this->defaultValue) $this->defaultValue = rtrim($strAddress, ',');

        $this->registerClientScript();
        $content .= Html::endTag('div');
        $content .=Html::beginTag('div', ['id' => 'cascade']);
        $content .= ' <el-cascader
            :options="options"
            filterable
            v-model="selectedOptions"
            clearable
            @change="handleChange"
          >
          </el-cascader>';
        $content .= Html::endTag('div');
        return $content;
    }

    /**
     * Registers required script for the plugin to work as jQuery File Uploader
     */
    public function registerClientScript()
    {
        VueAsset::register($this->getView());
        $options = Json::encode($this->cascadeData);
        $changeValue = '';
        foreach ($this->attributes as $k => $attribute) {
            $changeValue .= '$("#'.$attribute.'").val(value['.$k.']);'.PHP_EOL;
        }
        $js = <<<__SCRIPT
var objVue{$this->jsVariableName} = new Vue({
    el: '#cascade',
    data: function () {
        return {
            options: {$options},
            selectedOptions: [{$this->defaultValue}]
        };
    },
    methods: {
        handleChange: function (value) {
            {$changeValue}
        }
    }
})
__SCRIPT;

        $this->getView()->registerJs($js, View::POS_END);
    }

}