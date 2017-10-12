<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_item_stores".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $store_id
 * @property integer $partner_id
 * @property integer $spu_id
 * @property string $create_time
 */
class SkuItemStores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_item_stores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'store_id', 'partner_id', 'spu_id'], 'required'],
            [['item_id', 'store_id', 'partner_id', 'spu_id'], 'integer'],
            [['create_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'item_id' => 'spu_partner表中的id',
            'store_id' => '销售的门店',
            'partner_id' => 'Partner ID',
            'spu_id' => 'Spu ID',
            'create_time' => 'Create Time',
        ];
    }

    public function getStore()
    {
        return $this->hasOne(Store::className(),['id'=>'store_id']);
    }
}
