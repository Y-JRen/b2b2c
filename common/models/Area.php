<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "s_area".
 *
 * @property string $AREA_CODE
 * @property string $AREA_NAME
 * @property string $PARENT_CODE
 * @property string $PIN_YIN
 * @property string $JIAN_PIN
 * @property string $FIRST_CHAR
 * @property string $LNG
 * @property string $LAT
 * @property string $REMARK
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 's_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AREA_CODE'], 'required'],
            [['AREA_CODE', 'PARENT_CODE'], 'string', 'max' => 6],
            [['AREA_NAME', 'PIN_YIN', 'JIAN_PIN', 'LNG', 'LAT', 'REMARK'], 'string', 'max' => 50],
            [['FIRST_CHAR'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AREA_CODE' => '区域编号',
            'AREA_NAME' => '区域名称',
            'PARENT_CODE' => '上级区域编号',
            'PIN_YIN' => '城市对应拼音',
            'JIAN_PIN' => '简称',
            'FIRST_CHAR' => '首字母',
            'LNG' => '精度',
            'LAT' => '纬度',
            'REMARK' => '备注',
        ];
    }
}
