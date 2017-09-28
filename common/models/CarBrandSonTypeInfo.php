<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "car_brand_son_type_info".
 *
 * @property integer $car_brand_son_type_id
 * @property string $car_brand_son_type_name
 * @property string $pic_url
 * @property integer $car_brand_id
 * @property integer $car_brand_type_id
 * @property integer $son_type_id
 * @property string $son_type
 * @property string $factory_price
 * @property string $bottom_price
 * @property integer $sale_state
 * @property integer $specid
 * @property string $insert_time
 * @property string $update_time
 */
class CarBrandSonTypeInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'car_brand_son_type_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_brand_id', 'car_brand_type_id', 'son_type_id', 'sale_state', 'specid'], 'integer'],
            [['factory_price', 'bottom_price'], 'number'],
            [['insert_time', 'update_time'], 'safe'],
            [['car_brand_son_type_name', 'son_type'], 'string', 'max' => 50],
            [['pic_url'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'car_brand_son_type_id' => '主键',
            'car_brand_son_type_name' => '名称',
            'pic_url' => '图片路径',
            'car_brand_id' => '品牌ID',
            'car_brand_type_id' => '所属车系',
            'son_type_id' => 'Son Type ID',
            'son_type' => '所属车型',
            'factory_price' => '指导价',
            'bottom_price' => '参考价',
            'sale_state' => '销售状态，0：停售，1：正在销售',
            'specid' => '汽车之家specid',
            'insert_time' => 'Insert Time',
            'update_time' => 'Update Time',
        ];
    }
}
