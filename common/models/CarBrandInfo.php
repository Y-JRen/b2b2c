<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "car_brand_info".
 *
 * @property integer $car_brand_id
 * @property string $car_brand_name
 * @property string $pic_url
 * @property string $first_num
 * @property string $py
 * @property string $pyem
 * @property integer $is_used
 * @property string $source_pic_url
 * @property integer $is_display
 * @property integer $autohome_brand_id
 * @property string $insert_time
 * @property string $update_time
 */
class CarBrandInfo extends \yii\db\ActiveRecord
{
    /**
     * 是否使用中状态说明
     */
    const IS_USED_YES = 1; // 使用中
    const IS_USER_NO = 0;   // 未使用

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'car_brand_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_used', 'is_display', 'autohome_brand_id'], 'integer'],
            [['insert_time', 'update_time'], 'safe'],
            [['car_brand_name', 'pic_url'], 'string', 'max' => 255],
            [['first_num'], 'string', 'max' => 2],
            [['py'], 'string', 'max' => 10],
            [['pyem'], 'string', 'max' => 50],
            [['source_pic_url'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'car_brand_id' => '品牌ID',
            'car_brand_name' => '品牌名称',
            'pic_url' => '图片路径',
            'first_num' => '首字母',
            'py' => '拼音',
            'pyem' => '拼音',
            'is_used' => '0：未使用，1：使用中',
            'source_pic_url' => 'Source Pic Url',
            'is_display' => '新车显示的品牌列表',
            'autohome_brand_id' => 'Autohome Brand ID',
            'insert_time' => '创建时间',
            'update_time' => '完成时间',
        ];
    }
}
