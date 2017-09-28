<?php

namespace common\models;

use common\logic\PartnerLogic;
use yii\base\Model;

/**
 * Class DealerForm
 * @package common\models
 */
class DealerForm extends Model
{
    public $partner_id = null;

    public $dealer = [];

    /**
     * 设置合作商ID
     *
     * @param $id
     */
    public function setPartnerId($id)
    {
        $this->partner_id = $id;
        // 查询合作商的厂商信息
        $this->dealer = PartnerLogic::instance()->getPartnerFactoryIds($id);
    }

    public function attributeLabels()
    {
        return [
            'partner_id' => '合作商ID',
            'dealer' => '厂商'
        ];
    }

    public function rules()
    {
        return [
            [['partner_id', 'dealer'], 'required']
        ];
    }
}