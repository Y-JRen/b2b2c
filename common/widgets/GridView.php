<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/26
 * Time: 14:54
 */

namespace common\widgets;

use Yii;

/**
 * Class GridView
 * @package common\widgets
 */
class GridView extends \yii\grid\GridView
{
    /**
     * 表单样式
     *
     * @var array
     */
    public $tableOptions = ['class' => 'table table-hover table-bordered table-list-check'];
}