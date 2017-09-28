<?php

namespace common\logic;

use common\models\PartnerSellerStore;
use common\models\SkuItem;
use common\models\SkuItemStores;
use common\models\Store;
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
     * @param integer $intSpuId     spuID
     * @param integer $intPartnerId 合作商ID
     * @return bool
     */
    public function synchronizedStoresToSkuItem($intSupItemId, $intSpuId = 0, $intPartnerId = 0)
    {
        $isReturn = false;

        // 第一步判断合作商和spu唯一ID是否存在
        if (empty($intSpuId) || empty($intPartnerId)) {
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

    /**
     * 修改spu item 的门店信息
     *
     * @param integer $intSupItemId spu item 唯一ID
     * @param integer $intStoreId 门店ID
     * @return bool
     */
    public function updateSpuItemStore($intSupItemId, $intStoreId)
    {
        $isReturn = false;
        if ($intStoreId && $intSupItemId) {
            // 查询是否已经添加过
            $isReturn = SkuItemStores::find()->where([
                'item_id' => $intSupItemId,
                'store_id' => $intStoreId
            ])->asArray()->one();

            // 没有存在新增
            if (!$isReturn) {
                // 查询spu item 数据
                $arrItem = SkuItem::findOne($intSupItemId);
                if ($arrItem) {
                    $one = new SkuItemStores();
                    $one->partner_id = $arrItem->partner_id;
                    $one->spu_id = $arrItem->spu_id;
                    $one->store_id = $intStoreId;
                    $one->item_id = $arrItem->id;
                    $isReturn = $one->save();
                } else {
                    $isReturn = false;
                }
            }
        }

        return $isReturn;
    }

    /**
     * 删除sku item 门店信息
     * @param integer $id sku item Id
     * @return int
     */
    public function deleteSpuItemStore($id)
    {
        return SkuItemStores::deleteAll(['id' => $id]);
    }

    /**
     * 查询spu 可以选择的门店信息（就是对外的，不是自己的门店）
     *
     * @param integer $id spu item ID
     * @param integer $intPartnerId 合作商ID
     * @param string $strAddress 地址查询
     * @param array $select 查询字段信息默认 id, name
     * @return array
     */
    public function findCanChooseSpuItemStore($id, $intPartnerId, $strAddress = '', $select = ['t.id', 't.name'])
    {
        $query = (new Query())->select(['store_id'])->from('sku_item_stores')->where(['item_id' => $id]);

        // 默认查询条件
        $where = [
            'and',
            ['=', 't.status', Store::STATUS_ACTIVE],
            ['not in', 't.id', $query],
            ['=', 'p.partner_id', $intPartnerId]
        ];

        if ($strAddress) {
            $arrAddress = explode(',', $strAddress);
            if (!empty($arrAddress[0])) $where[] = ['=', 't.province_code', (int)$arrAddress[0]];
            if (!empty($arrAddress[1])) $where[] = ['=', 't.city_code', (int)$arrAddress[1]];
            if (!empty($arrAddress[2])) $where[] = ['=', 't.area_code', (int)$arrAddress[2]];
        }

        // 查询门店信息
        return (new Query())->select($select)
            ->from('partner_seller_store p')
            ->innerJoin('store t', '`t`.`id` = `p`.`store_id`')
            ->where($where)
            ->all();
    }
}