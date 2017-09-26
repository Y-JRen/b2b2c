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
class ActiveSearchField extends \yii\widgets\ActiveField
{
    public $options = ['class' => 'form-group col-md-4'];
    
    
    /**
     * label 样式
     *
     * @var array
     */
    public $labelOptions =  ['class' => 'control-label col-md-3'];
    
    /**
     * 表单样式
     *
     * @var string
     */
    public $template = '{label} <div class="col-lg-9">{input}{error}{hint}</div>';
}