<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_item_financial_lease".
 *
 * @property string $id
 * @property integer $down_payment
 * @property integer $month_period
 * @property integer $month_lease_fee
 * @property integer $tail_fee
 * @property integer $tail_pay_period
 * @property integer $tail_month_pay_fee
 * @property integer $server_charge
 * @property integer $item_id
 * @property integer $partner_id
 * @property integer $spu_id
 * @property string $create_time
 * @property string $create_person
 */
class SkuItemFinancialLease extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_item_financial_lease';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['down_payment', 'month_period', 'month_lease_fee', 'tail_fee', 'tail_pay_period', 'tail_month_pay_fee', 'item_id', 'partner_id', 'spu_id'], 'required'],
            [['down_payment', 'month_period', 'month_lease_fee', 'tail_fee', 'tail_pay_period', 'tail_month_pay_fee', 'server_charge', 'item_id', 'partner_id', 'spu_id'], 'integer'],
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
            'server_charge' => '服务费',
            'item_id' => 'Item ID',
            'partner_id' => '合作商',
            'spu_id' => 'Spu ID',
            'create_time' => '创建时间',
            'create_person' => '创建人',
        ];
    }
}
