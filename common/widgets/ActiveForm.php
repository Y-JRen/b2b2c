<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/25
 * Time: 10:29
 */

namespace common\widgets;


/**
 * 重写 \yii\widgets\ActiveForm
 *
 * Class ActiveForm
 * @package common\widgets
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
    /**
     * 保单相关样式
     *
     * @var string
     */
    public $fieldClass = 'common\widgets\ActiveField';
    
}