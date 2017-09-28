<?php

namespace common\models;

use Yii;

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
 * @property string $sell_type
 * @property string $spu_type
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
            [['spu_id', 'partner_id', 'des', 'status', 'subname', 'sell_type', 'spu_type'], 'required'],
            [['spu_id', 'partner_id', 'status', 'deposit'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['des', 'name', 'subname', 'sell_type', 'spu_type'], 'string', 'max' => 255],
            [['spu_id', 'partner_id'], 'unique', 'targetAttribute' => ['spu_id', 'partner_id'], 'message' => 'The combination of Spu ID and 合作商 has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'spu_id' => 'Spu ID',
            'partner_id' => '合作商',
            'des' => '商户自己的spu的描述',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'status' => '状态',
            'name' => '商品名称',
            'subname' => 'Subname',
            'deposit' => 'Deposit',
            'sell_type' => '销售方式，汽车类的有 normal 普通 lease 租赁两种方式',
            'spu_type' => '冗余字段  spu的类型',
        ];
    }
}
