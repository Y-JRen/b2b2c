<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sku_item".
 *
 * @property integer $id
 * @property integer $spu_id
 * @property integer $partner_id
 * @property string $des
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property string $name
 * @property string $subname
 * @property integer $deposit
 * @property integer $spu_type_id
 * @property integer $item_type_id
 * @property integer $down_payment
 * @property integer $month_payment
 * @property string $image
 */
class SkuItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spu_id', 'partner_id', 'status', 'spu_type_id', 'item_type_id', 'down_payment', 'month_payment'], 'required'],
            [['spu_id', 'partner_id', 'status', 'deposit', 'spu_type_id', 'item_type_id', 'down_payment', 'month_payment'], 'integer'],
            [['des','image'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'subname'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'spu_id' => '商品ID',
            'partner_id' => '合作商',
            'des' => '商户自己的spu的描述',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'status' => '状态',
            'name' => '商品名称',
            'subname' => '商品副标题',
            'deposit' => '定金',
            'spu_type_id' => '冗余字段  spu的类型',
            'item_type_id' => 'Item Type ID',
            'down_payment' => '估算的首付',
            'month_payment' => '估算的月供',
        ];
    }
    
    /**
     * 行为
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'value' => date("Y-m-d H:i:s"),
            ]
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpuType()
    {
        return $this->hasOne(SkuSpuType::className(), ['id' => 'spu_type_id']);
    }
}
