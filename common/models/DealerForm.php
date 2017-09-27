<?php

namespace common\models;

use yii\base\Model;

/**
 * Class DealerForm
 * @package common\models
 */
class DealerForm extends Model
{
    public $dealer = [];

    public function attributeLabels()
    {
        return ['dealer' => '厂商'];
    }
}