<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partner_identity".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property integer $identity_id
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class PartnerIdentity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_identity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id', 'identity_id', 'status'], 'required'],
            [['partner_id', 'identity_id', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'partner_id' => 'Partner ID',
            'identity_id' => 'Identity ID',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
