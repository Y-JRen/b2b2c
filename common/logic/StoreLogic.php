<?php

namespace common\logic;

use common\models\PartnerSellerStore;

/**
 * Class StoreLogic 门店相关的一些逻辑
 * @package common\logic
 */
class StoreLogic extends Instance
{
    /**
     * 添加门店和合作商的关联关系
     *
     * @param integer $intStoreId 门店ID
     * @param integer $intPartnerId 合作商ID
     * @param integer $self 是否自己的合作商
     * @return bool 是否添加成功
     */
    public function createStorePartner($intStoreId, $intPartnerId, $self = 1)
    {
        // 存在不做修改处理
        if (!PartnerSellerStore::findOne(['store_id' => $intStoreId, 'partner_id' => $intPartnerId])) {
            $one = new PartnerSellerStore();
            $one->store_id = $intStoreId;
            $one->partner_id = $intPartnerId;
            $one->is_partner_self = $self;
            $isTrue = $one->save();
        } else {
            $isTrue = false;
        }

        return $isTrue;
    }

    /**
     * 删除门店和合作商的关联关系
     *
     * @param integer $intStoreId
     * @return int 删除数据条数
     */
    public function deleteStorePartner($intStoreId)
    {
        return PartnerSellerStore::deleteAll(['store_id' => $intStoreId]);
    }
}