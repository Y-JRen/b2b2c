<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "car_base_conf_color".
 *
 * @property integer $id
 * @property integer $car_id
 * @property string $name
 * @property string $value
 * @property integer $type
 */
class CarBaseConfColor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'car_base_conf_color';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_id', 'type'], 'integer'],
            [['name', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_id' => '主键',
            'name' => 'Name',
            'value' => 'Value',
            'type' => '0外观颜色1内饰颜色',
        ];
    }
}
