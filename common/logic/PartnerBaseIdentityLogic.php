<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/27
 * Time: 11:53
 */

namespace common\logic;


use common\models\PartnerBaseIdentity;
use yii\helpers\ArrayHelper;

class PartnerBaseIdentityLogic extends Instance
{
    /**
     * 合作商权限 key => value
     *
     * @return array
     */
    public function getMenu()
    {
        $data = $this->getAllPartnerBaseIdentity();
        return ArrayHelper::map($data, 'id', 'name');
    }
    
    /**
     * 合作商权限
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAllPartnerBaseIdentity()
    {
        return $data = PartnerBaseIdentity::find()->all();
    }
}