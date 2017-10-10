<?php

namespace common\models;

use common\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "sku_spu_car".
 *
 * @property integer $id
 * @property string $create_time
 * @property string $update_time
 * @property integer $brand_id
 * @property string $brand_name
 * @property integer $factory_id
 * @property string $factory_name
 * @property integer $series_id
 * @property string $series_name
 * @property integer $car_type_id
 * @property string $car_type_name
 * @property integer $spu_id
 */
class SkuSpuCar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_spu_car';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time'], 'safe'],
            [['brand_id', 'brand_name', 'factory_id', 'factory_name', 'series_id', 'series_name', 'car_type_id', 'car_type_name', 'spu_id'], 'required'],
            [['brand_id', 'factory_id', 'series_id', 'car_type_id', 'spu_id'], 'integer'],
            [['brand_name', 'factory_name', 'series_name', 'car_type_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增主键',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'brand_id' => '汽车品牌id',
            'brand_name' => '汽车品牌',
            'factory_id' => '厂商id',
            'factory_name' => '厂商',
            'series_id' => '车系id',
            'series_name' => '车系',
            'car_type_id' => '车型id',
            'car_type_name' => '车型',
            'spu_id' => '所属spu的id',
        ];
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * 获取车型其他信息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBrandSonTypeInfo()
    {
        return $this->hasOne(CarBrandSonTypeInfo::className(), ['car_brand_son_type_id' => 'car_type_id']);
    }
}
