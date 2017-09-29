<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "financial_program_info".
 *
 * @property string $id
 * @property integer $financial_id
 * @property integer $ratio_id
 * @property string $ratio_name
 * @property integer $period_id
 * @property string $period_name
 * @property double $rate
 */
class FinancialProgramInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'financial_program_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['financial_id', 'ratio_id', 'ratio_name', 'period_id', 'period_name', 'rate'], 'required'],
            [['financial_id', 'ratio_id', 'period_id'], 'integer'],
            [['rate'], 'number'],
            [['ratio_name', 'period_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'financial_id' => 'Financial ID',
            'ratio_id' => 'Ratio ID',
            'ratio_name' => 'Ratio Name',
            'period_id' => 'Period ID',
            'period_name' => 'Period Name',
            'rate' => 'Rate',
        ];
    }
}
