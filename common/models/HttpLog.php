<?php

namespace common\models;

use common\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "http_log".
 *
 * @property integer $id
 * @property string $url
 * @property string $input_data
 * @property string $result
 * @property string $error
 * @property string $create_time
 */
class HttpLog extends ActiveRecord
{
    /**
     * 定义行为
     *
     * @return array
     */
    public function behaviors()
    {
        // 定义行为,自动维护 create_time 和 update_time 字段
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_time']
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'http_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['input_data', 'result'], 'string'],
            [['create_time'], 'safe'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'input_data' => 'Input Data',
            'result' => 'Result',
            'error' => '错误信息',
            'create_time' => 'Create Time',
        ];
    }
}
