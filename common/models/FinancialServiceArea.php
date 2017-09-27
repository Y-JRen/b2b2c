<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "financial_service_area".
 *
 * @property integer $id
 * @property string $area_name
 * @property string $area_code
 * @property integer $financial_id
 * @property integer $status
 * @property integer $is_delete
 */
class FinancialServiceArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'financial_service_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'area_code', 'financial_id', 'is_delete'], 'required'],
            [['id', 'financial_id', 'status', 'is_delete'], 'integer'],
            [['area_name', 'area_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'area_name' => 'Area Name',
            'area_code' => 'Area Code',
            'financial_id' => 'Financial ID',
            'status' => 'Status',
            'is_delete' => 'Is Delete',
        ];
    }
}
