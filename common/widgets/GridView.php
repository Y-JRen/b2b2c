<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/26
 * Time: 14:54
 */

namespace common\widgets;


class GridView extends \yii\grid\GridView
{
    public $tableOptions = ['class' => 'table table-hover table-bordered table-list-check'];
}