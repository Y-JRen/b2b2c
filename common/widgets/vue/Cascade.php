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
        $this->registerClientScript();
        $content = Html::beginTag('div', ['class' => 'hidden']);
        foreach ($this->attributes as $attribute) {
            $content .= Html::hiddenInput(Html::getInputName($this->model, $attribute), $this->model->$attribute, ['id' => $attribute]);
        }
        $content .= Html::endTag('div');
        $content .=Html::beginTag('div', ['id' => 'cascade']);
        $content .= ' <el-cascader
            :options="options"
            v-model="selectedOptions"
            @change="handleChange">
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
        $options = json_encode($this->cascadeData);
        $changeValue = '';
        foreach ($this->attributes as $k => $attribute) {
            $changeValue .= '$("#'.$attribute.'").val(value['.$k.']);'.PHP_EOL;
        }
        $js = <<<__SCRIPT
new Vue({
    el: '#cascade',
    data: function () {
        return {
            options: {$options},
            selectedOptions: []
        };
    },
    methods: {
        handleChange: function (value) {
            {$changeValue}
        }
    }
})
__SCRIPT;

        $this->getView()->registerJs($js);
    }

}