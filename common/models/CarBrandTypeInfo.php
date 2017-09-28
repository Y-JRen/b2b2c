<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "car_brand_type_info".
 *
 * @property integer $car_brand_type_id
 * @property string $car_brand_type_name
 * @property string $pic_url
 * @property integer $car_brand_id
 * @property string $zhi_dao_price
 * @property string $min_price
 * @property string $max_price
 * @property string $car_model_name
 * @property integer $car_model
 * @property string $url
 * @property integer $is_display
 * @property integer $autohome_brand_id
 * @property integer $autohome_brand_type_id
 * @property integer $factory_id
 * @property string $insert_time
 * @property string $update_time
 */
class CarBrandTypeInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'car_brand_type_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_brand_id', 'car_model', 'is_display', 'autohome_brand_id', 'autohome_brand_type_id', 'factory_id'], 'integer'],
            [['min_price', 'max_price'], 'number'],
            [['insert_time', 'update_time'], 'safe'],
            [['car_brand_type_name'], 'string', 'max' => 50],
            [['pic_url', 'zhi_dao_price', 'car_model_name', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'car_brand_type_id' => '车系ID',
            'car_brand_type_name' => '车系名',
            'pic_url' => '图片路径',
            'car_brand_id' => '品牌ID',
            'zhi_dao_price' => '指导价。例如：10.00-110.00万',
            'min_price' => 'Min Price',
            'max_price' => 'Max Price',
            'car_model_name' => '车系类型名',
            'car_model' => '车系车型',
            'url' => '汽车之家车系url',
            'is_display' => '新车显示的车系列表',
            'autohome_brand_id' => '汽车之家品牌id',
            'autohome_brand_type_id' => '汽车之家车系id',
            'factory_id' => '厂商id',
            'insert_time' => 'Insert Time',
            'update_time' => 'Update Time',
        ];
    }
}
