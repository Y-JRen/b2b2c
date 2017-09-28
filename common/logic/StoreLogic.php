<?php

namespace common\logic;

use common\models\PartnerSellerStore;
use Yii;
use yii\db\Expression;
use yii\db\Query;

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

    /**
     * 添加了一个spu 后，同步合作商的自营门店信息
     *
     * @param integer $intSupItemId 合作商和spu 唯一ID
     * @param integer $intSpuId     supID
     * @param integer $intPartnerId 合作商ID
     * @return bool
     */
    public function synchronizedStoresToSup($intSupItemId, $intSpuId = 0, $intPartnerId = 0)
    {
        $isReturn = false;

        // 第一步判断合作商和sup唯一ID是否存在
        if (empty($intSpuId) || $intPartnerId) {
            $arrItem = (new Query())->from('sku_item')->where(['id' => $intSupItemId])->one();
            if ($arrItem) {
                $intSpuId = $arrItem['spu_id'];
                $intPartnerId = $arrItem['partner_id'];
            }
        }

        // 第二步开始执行同步数据
        if ($intSupItemId && $intPartnerId && $intSpuId) {
            // 查询这个合作商所有的自营门店信息
            $array = PartnerSellerStore::find()->select(['store_id', 'partner_id'])->where([
                'partner_id' => $intPartnerId,
                'is_partner_self' => PartnerSellerStore::IS_PARTNER_SELF_YES
            ])->asArray()->all();
            if ($array) {
                $arrInsert = [];
                $time = new Expression('CURRENT_TIMESTAMP()');
                foreach ($array as $value) {
                    $arrInsert[] = [
                        'item_id' => $intSupItemId,
                        'store_id' => $value['store_id'],
                        'partner_id' => $value['partner_id'],
                        'spu_id' => $intSpuId,
                        'create_time' => $time
                    ];
                }

                // 执行批量导入
                $isReturn = Yii::$app->db->createCommand()->batchInsert('sku_item_stores', [
                    'item_id', 'store_id', 'partner_id', 'spu_id', 'create_time'
                ], $arrInsert)->execute();
            }
        }


        return $isReturn;
    }
}