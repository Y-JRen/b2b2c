<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sku_item_attachment".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $type
 * @property string $url
 * @property string $create_time
 * @property integer $status
 * @property integer $spu_id
 * @property integer $partner_id
 * @property string $create_person
 */
class SkuItemAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sku_item_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_id', 'type', 'url', 'status', 'spu_id', 'partner_id', 'create_person'], 'required'],
            [['id', 'item_id', 'status', 'spu_id', 'partner_id'], 'integer'],
            [['create_time'], 'safe'],
            [['type', 'url', 'create_person'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'sku 的id',
            'type' => '附件类型',
            'url' => '附件地址',
            'create_time' => '创建时间',
            'status' => '附件状态',
            'spu_id' => '冗余字段',
            'partner_id' => '冗余字段',
            'create_person' => 'Create Person',
        ];
    }
}
