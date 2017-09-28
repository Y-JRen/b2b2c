<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/15
 * Time: 10:59
 */

namespace common\logic;


use common\models\Area;
use yii\helpers\ArrayHelper;

/**
 * 地址相关逻辑
 *
 * Class AreaLogic
 * @package backend\logic
 */
class AreaLogic extends Instance
{
    /**
     * 省 key => value
     *
     * @return array
     */
    public function getProvinceMenu()
    {
        return ArrayHelper::map($this->getProvince(), 'AREA_CODE', 'AREA_NAME');
    }
    
    /**
     * @return array|Area[]|\yii\db\ActiveRecord[]
     */
    public function getProvince()
    {
        $cache = \Yii::$app->cache;
        if(!$province = $cache->get("AREA_PROVINCE")) {
            $province = Area::find()->where(['PARENT_CODE' => '000000'])->all();
            $cache->set('AREA_PROVINCE', $province);
        }
        return $province;
    }
    
    /**
     * 地址树形结构
     *
     * @param int $level
     *
     * @return array|mixed
     */
    public function getAreaTree($level = 1)
    {
        $cache = \Yii::$app->cache;
        if(!$tree = $cache->get("AREA_TREE_".$level)) {
            $tree = [];
            foreach ($this->getProvince() as $k => $v) {
                $children = $this->getChildren($v, $level);
                $tree[$k] = [
                    'value' => $v->AREA_CODE,
                    'label' => $v->AREA_NAME,
                ];
                if ($children) {
                    $tree[$k]['children'] = $children;
                }
            }
            $cache->set("AREA_TREE_".$level, $tree);
        }
        return $tree;
    }
    
    /**
     * 获取子树
     *
     * @param $area
     * @param int $level
     *
     * @return array
     */
    public function getChildren($area, $level = 1)
    {
        $area= Area::find()->where(['PARENT_CODE' => $area->AREA_CODE])->all();
        $returnData = [];
        $i = 1;
        foreach ($area as  $k => $v) {
            $returnData[$k] = [
                'value' => $v->AREA_CODE,
                'label' => $v->AREA_NAME,
            ];
            if ($i < $level) {
                $children = $this->getChildren($v, $i++);
                if ($children) {
                    $returnData[$k]['children'] = $children;
                }
            }
        }
        return $returnData;
    }
}