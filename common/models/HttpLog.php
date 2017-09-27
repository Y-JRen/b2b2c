<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "http_log".
 *
 * @property integer $id
 * @property string $url
 * @property string $inputData
 * @property string $result
 * @property string $create_time
 */
class HttpLog extends \yii\db\ActiveRecord
{
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
            [['inputData', 'result'], 'string'],
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
            'inputData' => 'Input Data',
            'result' => 'Result',
            'create_time' => 'Create Time',
        ];
    }
}
