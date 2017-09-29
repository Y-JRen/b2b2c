<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_sku".
 *
 * @property string $id
 * @property string $price
 * @property integer $spu_id
 * @property string $name
 * @property string $subname
 * @property integer $partner_id
 * @property string $deposit
 * @property integer $spu_partner_id
 * @property string $des
 * @property string $sell_type
 * @property string $spu_type
 * @property string $create_time
 * @property string $update_time
 * @property string $create_person
 * @property string $update_person
 * @property integer $status
 */
class SkuSku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_sku';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'spu_id', 'name', 'subname', 'partner_id', 'deposit', 'spu_partner_id', 'sell_type', 'spu_type', 'create_person', 'update_person'], 'required'],
            [['price', 'spu_id', 'partner_id', 'deposit', 'spu_partner_id', 'status'], 'integer'],
            [['des'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'subname', 'sell_type', 'spu_type', 'create_person', 'update_person'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'spu_id' => 'Spu ID',
            'name' => 'Name',
            'subname' => 'Subname',
            'partner_id' => 'Partner ID',
            'deposit' => 'Deposit',
            'spu_partner_id' => 'Spu Partner ID',
            'des' => 'Des',
            'sell_type' => 'Sell Type',
            'spu_type' => 'Spu Type',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'create_person' => 'Create Person',
            'update_person' => 'Update Person',
            'status' => 'Status',
        ];
    }
}
