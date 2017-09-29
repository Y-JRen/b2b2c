<?php

namespace common\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "financial_program".
 *
 * @property string $id
 * @property string $no
 * @property string $type
 * @property string $name
 * @property string $des
 * @property integer $partner_id
 * @property integer $foreign_service
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property integer $is_delete
 */
class FinancialProgram extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'financial_program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no', 'type', 'name', 'partner_id'], 'required'],
            [['des'], 'string'],
            [['partner_id', 'foreign_service', 'status', 'is_delete'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['no'], 'string', 'max' => 20],
            [['type', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no' => 'No',
            'type' => 'Type',
            'name' => 'Name',
            'des' => 'Des',
            'partner_id' => 'Partner ID',
            'foreign_service' => 'Foreign Service',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'status' => 'Status',
            'is_delete' => 'Is Delete',
        ];
    }
	 
	public function findCount($filter = array() )
    {
        $where = ['=', 'is_delete', '0'];
        $query = $this->find()->where($where)->orderBy(['id' => SORT_DESC]);
        return $totalCount = $query->count();

    }
    public function search( $filter = array() )
    {
        $where = ['=', 'is_delete', '0'];
        $totalCount = $this->findCount($filter);
        $pagination = new Pagination(compact('totalCount')); 
        $query = $this->find()->where($where)->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
        return $query;
    }
}
