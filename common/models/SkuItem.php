<?php

namespace common\models;

use Yii;
use \yii\db\ActiveRecord;

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
 * @property string $spu_type_id
 */
class SkuItem extends ActiveRecord
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
            [['spu_id', 'partner_id', 'des', 'status', 'spu_type_id'], 'required'],
            [['spu_id', 'partner_id', 'status', 'deposit'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['des', 'name', 'subname', 'spu_type_id'], 'string', 'max' => 255],
            [['spu_id', 'partner_id'], 'unique', 'targetAttribute' => ['spu_id', 'partner_id'], 'message' => 'The combination of 商品ID and 合作商 has already been taken.'],
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
        ];
    }
}
