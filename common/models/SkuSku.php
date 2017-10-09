<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_sku".
 *
 * @property string $id
 * @property integer $price
 * @property integer $spu_id
 * @property string $name
 * @property string $subname
 * @property integer $partner_id
 * @property integer $deposit
 * @property integer $item_id
 * @property string $des
 * @property integer $item_type_id
 * @property integer $spu_type_id
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
            [['price', 'spu_id', 'name', 'partner_id', 'item_id', 'item_type_id', 'spu_type_id', 'create_person'], 'required'],
            [['price', 'spu_id', 'partner_id', 'deposit', 'item_id', 'item_type_id', 'spu_type_id', 'status'], 'integer'],
            [['des'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'subname', 'create_person', 'update_person'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'price' => '商品价格',
            'spu_id' => '所属的spu',
            'name' => '名称',
            'subname' => '副标题名称',
            'partner_id' => '所属合作商',
            'deposit' => '订金',
            'item_id' => 'spu+partner表的id',
            'des' => '商品描述',
            'item_type_id' => '销售方式，汽车类的有 normal 普通 lease 租赁两种方式',
            'spu_type_id' => '冗余字段  spu的类型',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'create_person' => 'Create Person',
            'update_person' => 'Update Person',
            'status' => '状态 0 - 下架 1 上架',
        ];
    }
    
    /**
     * sku信息 ，如：内外色
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParameter()
    {
        return $this->hasMany(SkuParameterAndValue::className(), ['sku_id' => 'id']);
    }
}
