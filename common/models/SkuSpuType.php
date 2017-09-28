<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_spu_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $ename
 */
class SkuSpuType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_spu_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'ename'], 'required'],
            [['name', 'ename'], 'string', 'max' => 255],
            [['ename'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增主键',
            'name' => '产品类型名称',
            'ename' => '产品类型 - ename',
        ];
    }
}
