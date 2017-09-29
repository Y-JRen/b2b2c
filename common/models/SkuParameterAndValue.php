<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_sku_parameter_and_value".
 *
 * @property integer $id
 * @property integer $parameter_id
 * @property string $parameter_name
 * @property integer $value_id
 * @property string $value_name
 * @property integer $sku_id
 * @property string $create_time
 */
class SkuParameterAndValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_sku_parameter_and_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parameter_id', 'parameter_name', 'value_id', 'value_name', 'sku_id'], 'required'],
            [['parameter_id', 'value_id', 'sku_id'], 'integer'],
            [['create_time'], 'safe'],
            [['parameter_name', 'value_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增主键',
            'parameter_id' => '参数id',
            'parameter_name' => '参数名，冗余',
            'value_id' => '参数的值的id',
            'value_name' => '参数的值,冗余',
            'sku_id' => 'Sku ID',
            'create_time' => 'Create Time',
        ];
    }
}
