<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_spu_partner".
 *
 * @property integer $id
 * @property integer $spu_id
 * @property integer $partner_id
 * @property string $des
 * @property string $create_time
 */
class SkuSpuPartner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_spu_partner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spu_id', 'partner_id'], 'required'],
            [['spu_id', 'partner_id'], 'integer'],
            [['create_time'], 'safe'],
            [['des'], 'string', 'max' => 255],
            [['spu_id', 'partner_id'], 'unique', 'targetAttribute' => ['spu_id', 'partner_id'], 'message' => 'The combination of Spu ID and Partner ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'spu_id' => 'Spu ID',
            'partner_id' => 'Partner ID',
            'des' => '商户自己的spu的描述',
            'create_time' => 'Create Time',
        ];
    }
}
