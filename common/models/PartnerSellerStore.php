<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\TimestampBehavior;

/**
 * This is the model class for table "partner_seller_store".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property integer $store_id
 * @property integer $is_partner_self
 * @property integer $create_time
 * @property integer $update_time
 */
class PartnerSellerStore extends ActiveRecord
{
    /**
     * 定义行为
     *
     * @return array
     */
    public function behaviors()
    {
        // 定义行为,自动维护 create_time 和 update_time 字段
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_seller_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'store_id', 'is_partner_self'], 'required'],
            [['partner_id', 'store_id', 'is_partner_self', 'create_time', 'update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'partner_id' => '合作商id',
            'store_id' => '门店id',
            'is_partner_self' => '是否是合作商自己的门店 0 - 不是 1 是',
        ];
    }
}
