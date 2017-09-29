<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "financial_base_repament_period".
 *
 * @property string $id
 * @property string $name
 * @property integer $value
 * @property integer $status
 */
class FinancialBaseRepamentPeriod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'financial_base_repament_period';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
            'status' => 'Status',
        ];
    }
}
