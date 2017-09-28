<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/28
 * Time: 14:27
 */

namespace backend\models\form;


use yii\helpers\ArrayHelper;


/**
 * spu item 表单
 *
 *
 * Class SpuItemForm
 * @package backend\models\form
 */
class SpuItemForm extends SpuForm
{
    public $sku;
    
    public $item_financial;
    
    public function rules()
    {
        return [
            [['sku', 'deposit', 'item_financial'], 'required'],
            ['deposit', 'integer'],
            ['sku', 'checkSku'],
            ['item_financial', 'each', 'rule' => ['integer']]
        ];
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'item_financial' => '金融方案'
        ]);
    }
    
    /**
     * 验证sku相关信息
     *
     * @param $attribute
     */
    public function checkSku($attribute)
    {
        if(!is_array($this->$attribute)) {
            $this->addError($attribute, '参数格式错误');
        }
        foreach ($this->$attribute as $value) {
            if (!isset($value['inner_color']) || !isset($value['outer_color'])) {
                $this->addError($attribute, '参数错误');
            }
        }
    }
}