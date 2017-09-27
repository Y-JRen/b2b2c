<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "car_factory_info".
 *
 * @property integer $factory_id
 * @property string $factory_name
 */
class CarFactoryInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'car_factory_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'factory_id' => 'Factory ID',
            'factory_name' => '厂商名称',
        ];
    }
}
