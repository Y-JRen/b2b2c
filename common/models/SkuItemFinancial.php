<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_item_financial".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $financial_id
 * @property integer $spu_id
 * @property integer $partner_id
 * @property string $create_time
 */
class SkuItemFinancial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_item_financial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_id', 'financial_id', 'spu_id', 'partner_id'], 'required'],
            [['id', 'item_id', 'financial_id', 'spu_id', 'partner_id'], 'integer'],
            [['create_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'spu_partner表的id',
            'financial_id' => '可用的金融方案id',
            'spu_id' => 'Spu ID',
            'partner_id' => 'Partner ID',
            'create_time' => 'Create Time',
        ];
    }
}
