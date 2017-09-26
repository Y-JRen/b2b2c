<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property integer $id
 * @property string $name
 * @property string $province_code
 * @property string $province_name
 * @property string $city_code
 * @property string $city_name
 * @property string $area_code
 * @property string $area_name
 * @property string $address
 * @property string $contact_person
 * @property string $contact_phone
 * @property string $lon
 * @property string $lat
 * @property integer $status
 * @property integer $foreign_service
 * @property integer $partner_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $is_delete
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'province_code', 'province_name', 'city_code', 'city_name', 'area_code', 'area_name', 'address', 'contact_person', 'contact_phone', 'lon', 'lat', 'status', 'foreign_service', 'partner_id', 'is_delete'], 'required'],
            [['status', 'foreign_service', 'partner_id', 'is_delete'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'province_name', 'city_name', 'area_name', 'address', 'contact_person', 'lon', 'lat'], 'string', 'max' => 255],
            [['province_code', 'city_code', 'area_code'], 'string', 'max' => 6],
            [['contact_phone'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '门店名',
            'province_code' => '省份 code',
            'province_name' => '省份',
            'city_code' => '市code',
            'city_name' => '市名',
            'area_code' => '地区code',
            'area_name' => '地区名',
            'address' => '门店详细地址',
            'contact_person' => '联系人',
            'contact_phone' => '联系电话',
            'lon' => '经度',
            'lat' => '纬度',
            'status' => '状态 0 - 无效 1 - 有效',
            'foreign_service' => '是否提供给本合作商外的合作商使用',
            'partner_id' => '合作商id',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'is_delete' => '是否被删除 0 - 没有删除  1 - 删除了',
        ];
    }
}
