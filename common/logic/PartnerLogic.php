<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/27
 * Time: 18:50
 */

namespace common\logic;


use common\models\Partner;
use yii\helpers\ArrayHelper;

/**
 * 经销商相关逻辑
 *
 * Class PartnerLogic
 * @package common\logic
 */
class PartnerLogic extends Instance
{
    /**
     * 经销商 key => value
     *
     * @return array
     */
    public function getPartnerMenu()
    {
        return ArrayHelper::map($this->getAllPartner(), 'id', 'name');
    }
    
    /**
     * 所有经销商
     *
     * @return array|Partner[]|\yii\db\ActiveRecord[]
     */
    public function getAllPartner()
    {
        return Partner::find()->all();
    }
}