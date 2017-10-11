<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_sku_financial_lease".
 *
 * @property string $id
 * @property integer $down_payment
 * @property integer $month_period
 * @property integer $month_lease_fee
 * @property integer $tail_fee
 * @property integer $tail_pay_period
 * @property integer $tail_month_pay_fee
 * @property integer $service_charge
 * @property integer $sku_id
 * @property integer $partner_id
 * @property integer $spu_id
 * @property string $create_time
 * @property string $create_person
 */
class SkuFinancialLease extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_sku_financial_lease';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['down_payment', 'month_period', 'month_lease_fee', 'tail_fee', 'tail_pay_period', 'tail_month_pay_fee', 'sku_id', 'partner_id', 'spu_id'], 'required'],
            [['down_payment', 'month_period', 'month_lease_fee', 'tail_fee', 'tail_pay_period', 'tail_month_pay_fee', 'service_charge', 'sku_id', 'partner_id', 'spu_id'], 'integer'],
            [['create_time'], 'safe'],
            [['create_person'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'down_payment' => '首付款  精确到分',
            'month_period' => '租期，多少个月',
            'month_lease_fee' => '月租金',
            'tail_fee' => '尾款金额',
            'tail_pay_period' => '尾款分期期数',
            'tail_month_pay_fee' => '尾款月供金额',
            'service_charge' => '服务费',
            'sku_id' => 'sku关联ID',
            'partner_id' => '合作商',
            'spu_id' => 'spu',
            'create_time' => '创建时间',
            'create_person' => '创建人',
        ];
    }
}
