<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "car_brand_factory_info".
 *
 * @property integer $id
 * @property integer $factory_id
 * @property integer $brand_id
 */
class CarBrandFactoryInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'car_brand_factory_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_id', 'brand_id'], 'required'],
            [['factory_id', 'brand_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factory_id' => 'Factory ID',
            'brand_id' => 'Brand ID',
        ];
    }
}
