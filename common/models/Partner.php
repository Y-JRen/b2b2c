<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partner".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $logo
 * @property string $contact_person
 * @property string $contact_phone
 * @property string $create_time
 * @property string $update_time
 * @property string $description
 */
class Partner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address', 'logo', 'contact_person', 'contact_phone'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['description'], 'string'],
            [['name', 'address', 'logo', 'contact_person'], 'string', 'max' => 255],
            [['contact_phone'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id，合作商id',
            'name' => '合作商名称',
            'address' => '合作商地址',
            'logo' => 'logo',
            'contact_person' => '联系人',
            'contact_phone' => '联系电话',
            'create_time' => '创建时间',
            'update_time' => '信息更新时间',
            'description' => '合作商描述',
        ];
    }
}
