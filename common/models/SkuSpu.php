<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_spu".
 *
 * @property integer $id
 * @property string $create_time
 * @property string $update_time
 * @property string $name
 * @property integer $type_id
 */
class SkuSpu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_spu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time'], 'safe'],
            [['name', 'type_id'], 'required'],
            [['type_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增主键',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'name' => '产品名称，如iphone8',
            'type_id' => '产品类型id ,关联spu_type表',
        ];
    }
}
