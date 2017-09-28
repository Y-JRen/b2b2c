<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "partner_saller_car_factory".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property integer $factory_id
 * @property string $create_time
 * @property string $update_time
 */
class PartnerSallerCarFactory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_saller_car_factory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'factory_id'], 'required'],
            [['partner_id', 'factory_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'partner_id' => '合作商id',
            'factory_id' => '工厂id',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
