<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partner_base_identity".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property string $description
 */
class PartnerBaseIdentity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_base_identity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['status'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'name' => '身份名称',
            'status' => '状态， 0 - 暂停 1 - 使用中',
            'description' => 'Description',
        ];
    }
}
