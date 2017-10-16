<?php

namespace common\models;

use Yii;
use \yii\db\ActiveRecord;
use common\behaviors\TimestampBehavior;

/**
 * This is the model class for table "partner_apply_join_log".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $company_profile
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class PartnerApplyJoinLog extends ActiveRecord
{
    /**
     * 状态说明
     */
    const STATUS_DELETE = -1; // 删除
    const STATUS_PENDING = 0; // 待处理
    const STATUS_AUDIT_PASSED = 1; // 审核通过

    /**
     * 定义行为
     *
     * @return array
     */
    public function behaviors()
    {
        // 定义行为,自动维护 create_time 和 update_time 字段
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_apply_join_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['company_profile'], 'string'],
            [['status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['phone'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 60],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '申请加入ID',
            'name' => '申请人姓名',
            'phone' => '申请人手机号',
            'email' => '电子邮箱',
            'company_profile' => '公司介绍',
            'status' => '处理状态[0 未处理 1 已经审核 -1 删除]',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
        ];
    }
}
