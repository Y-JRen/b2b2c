<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/27
 * Time: 09:55
 */

namespace backend\models\form;

use common\models\Partner;
use yii\base\Model;

/**
 * 商户表单
 *
 * Class PartnerForm
 * @package backend\models\form
 */
class PartnerForm extends Model
{
    /**
     * 商户名称
     * @var string
     */
    public $name;
    
    /**
     * 商户地址
     * @var string
     */
    public $address;
    
    /**
     * 商户图片
     * @var string
     */
    public $logo;
    
    /**
     * 联系人
     * @var string
     */
    public $contact_person;
    
    /**
     * 联系电话
     * @var string
     */
    public $contact_phone;
    
    /**
     * 商户描述
     * @var string
     */
    public $description;
    
    /**
     * 商品权限
     * @var array
     */
    public $partner_identity = [];
    
    
    public function rules()
    {
        return [
            [['name', 'address', 'logo', 'contact_person', 'contact_phone'], 'required'],
            [['description'], 'string'],
            [['name', 'address', 'logo', 'contact_person'], 'string', 'max' => 255],
            [['contact_phone'], 'string', 'max' => 15],
            ['partner_identity', 'each', 'rule' => ['integer']]
        ];
    }
    
    public function save()
    {
        if ($this->validate()) {
            return false;
        }
        
    }
    
    public function partnerSave()
    {
        $partner = new Partner();
        $partner->name = $this->name;
        $partner->address = $this->address;
        $partner->logo = $this->logo;
        $partner->contact_person = $this->contact_person;
        $partner->contact_phone = $this->contact_phone;
        $partner->name = $this->name;
    }
}