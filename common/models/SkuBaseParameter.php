<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_sku_base_parameter".
 *
 * @property integer $id
 * @property integer $spu_id
 * @property string $name
 * @property string $create_time
 */
class SkuBaseParameter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_sku_base_parameter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spu_id', 'name'], 'required'],
            [['spu_id'], 'integer'],
            [['create_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增主键',
            'spu_id' => '所属spu的id',
            'name' => '参数变量名   如内色，外色',
            'create_time' => 'Create Time',
        ];
    }
    
    public function getValue()
    {
        return $this->hasMany(SkuBaseParameterValue::className(), ['parameter_id' => 'id']);
    }
}
