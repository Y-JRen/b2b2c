<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_sku_financial".
 *
 * @property string $id
 * @property string $sku_id
 * @property integer $financial_id
 * @property string $create_time
 */
class SkuSkuFinancial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_sku_financial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sku_id', 'financial_id'], 'required'],
            [['sku_id', 'financial_id'], 'integer'],
            [['create_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sku_id' => 'Sku ID',
            'financial_id' => 'Financial ID',
            'create_time' => 'Create Time',
        ];
    }
}
