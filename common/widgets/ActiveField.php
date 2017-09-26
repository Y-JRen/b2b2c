<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/25
 * Time: 10:33
 */

namespace common\widgets;


/**
 * 重写 \yii\widgets\ActiveField
 *
 * Class ActiveField
 * @package common\widgets
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * label 样式
     *
     * @var array
     */
    public $labelOptions =  ['class' => 'control-label col-lg-1'];
    
    /**
     * 表单样式
     *
     * @var string
     */
    public $template = '<div class="row">{label} <div class="col-lg-4">{input}{error}{hint}</div></div>';
}