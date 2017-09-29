<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_sku_base_parameter_value".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parameter_id
 * @property string $create_time
 */
class SkuBaseParameterValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_sku_base_parameter_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parameter_id'], 'required'],
            [['parameter_id'], 'integer'],
            [['create_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '值表，如红色 白色  金色等',
            'parameter_id' => '参数id',
            'create_time' => 'Create Time',
        ];
    }
}
