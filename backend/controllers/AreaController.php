<?php
/**
 * Created by PhpStorm.
 * User: YJR
 * Date: 2017/10/11
 * Time: 15:44
 */

namespace backend\controllers;

use common\logic\AreaLogic;
use Yii;
use yii\web\Controller;
use yii\bootstrap\Html;

class AreaController extends Controller
{
    public function actionGetChild($code, $typeid = 0)
    {
        $model = AreaLogic::instance()->getChildrenByParentCode($code);
        if($typeid == 1){$aa="--请选择市--";}else if($typeid == 2 && $model){$aa="--请选择区--";}

        echo Html::tag('option',$aa, ['value'=>'empty']) ;

        foreach($model as $value=>$name)
        {
            echo Html::tag('option',Html::encode($name),array('value'=>$value));
        }
    }
}