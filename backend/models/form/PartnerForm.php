<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/27
 * Time: 09:55
 */

namespace backend\models\form;

use common\models\Partner;
use common\models\PartnerIdentity;
use yii\behaviors\TimestampBehavior;
use yii\db\Exception;

/**
 * 商户表单
 *
 * Class PartnerForm
 * @package backend\models\form
 */
class PartnerForm extends Partner
{
    /**
     * 商品权限
     * @var array
     */
    public $partner_identity = [];
    
    /**
     * 数据验证
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'address', 'logo', 'contact_person', 'contact_phone', 'partner_identity'], 'required'],
            [['description'], 'string'],
            [['id'], 'integer'],
            [['name', 'address', 'logo', 'contact_person'], 'string', 'max' => 255],
            [['contact_phone'], 'string', 'max' => 15],
            ['partner_identity', 'each', 'rule' => ['integer']]
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => '商户ID',
            'name' => '商户名称',
            'address' => '商户地址',
            'logo' => '商户图片',
            'contact_person' => '联系人',
            'contact_phone' => '联系电话',
            'description' => '商户描述',
            'partner_identity' => '商户权限',
            'create_time' => '创建时间',
            'update_time' => '信息更新时间',
        ];
    }
    
    /**
     * 商户基本信息保存
     *
     * @return bool|Partner
     * @throws Exception
     */
    public function baseSave()
    {
        if (!$this->validate()) {
            return false;
        }
        $t = \Yii::$app->db->beginTransaction();
        try{
            $partner = $this->save();
            if (!$partner) {
                throw new Exception('商户基本信息保存');
            }
            $this->identitySave();
            $t->commit();
            return $partner;
        }catch (Exception $e){
            $t->rollBack();
            throw $e;
        }
    }
    
    
    /**
     * 保存商户权限
     *
     * @return bool
     */
    public function identitySave()
    {
        if (!$this->isNewRecord) {
            PartnerIdentity::deleteAll(['partner_id' => $this->id]);
        }
        $saveData = [];
        foreach ($this->partner_identity as $identity) {
            $saveData[] = [
                $this->id,
                $identity,
                1,
                date('Y-m-d H:i:s')
            ];
        }
        \Yii::$app->db->createCommand()->batchInsert(PartnerIdentity::tableName(), [
            'partner_id',
            'identity_id',
            'status',
            'create_time'
        ], $saveData)->execute();

        return false;
        
    }
    
    /**
     * 编辑model
     *
     * @param $id
     *
     * @return static
     */
    public function findModel($id)
    {
        return self::findOne($id);
    }
    
    /**
     * 商户对应的权限
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerIdentity()
    {
        return $this->hasMany(PartnerIdentity::className(), ['partner_id' => 'id']);
    }
    
    /**
     * 表相关行为
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }
}