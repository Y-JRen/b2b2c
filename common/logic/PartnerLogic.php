<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/9/27
 * Time: 18:50
 */

namespace common\logic;


use common\models\Partner;
use common\models\PartnerSallerCarFactory;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * 经销商相关逻辑
 *
 * Class PartnerLogic
 * @package common\logic
 */
class PartnerLogic extends Instance
{
    /**
     * 经销商 key => value
     *
     * @return array
     */
    public function getPartnerMenu()
    {
        return ArrayHelper::map($this->getAllPartner(), 'id', 'name');
    }
    
    /**
     * 所有经销商
     *
     * @return array|Partner[]|\yii\db\ActiveRecord[]
     */
    public function getAllPartner()
    {
        return Partner::find()->all();
    }

    /**
     * 获取合作商所有的厂商ID
     *
     * @param integer $id 合作商ID
     * @return array
     */
    public function getPartnerFactoryIds($id)
    {
        return PartnerSallerCarFactory::find()->select(['factory_id'])->where(['partner_id' => $id])->column();
    }

    /**
     * 修改合作商的厂商信息
     * @param integer $id
     * @param array $arrFactory
     * @return bool|mixed
     */
    public function updatePartnerFactory($id, $arrFactory)
    {
        $isReturn = false;
        if ($id && $arrFactory) {
            // 开启事务处理
            $isReturn = Yii::$app->db->transaction(function() use ($id, $arrFactory) {
                // 第一步删除多余数据
                PartnerSallerCarFactory::deleteAll([
                    'and',
                    ['=', 'partner_id', $id],
                    ['not in', 'factory_id', $arrFactory]
                ]);

                // 第二步排除之前已经有的厂商，不做修改处理
                $arrOldFactoryIds = $this->getPartnerFactoryIds($id);
                $arrInsertIds = array_diff($arrFactory, $arrOldFactoryIds);

                // 第三步，添加没有的数据
                if ($arrInsertIds) {
                    $arrInsert = [];
                    $time = new Expression('CURRENT_TIMESTAMP()');
                    foreach ($arrInsertIds as $value) {
                        if ($value) {
                            $arrInsert[] = [
                                'partner_id' => $id,
                                'factory_id' => $value,
                                'create_time' => $time,
                                'update_time' => $time,
                            ];
                        }
                    }

                    // 批量添加
                    if ($arrInsert) {
                        return Yii::$app->db->createCommand()->batchInsert('partner_saller_car_factory', [
                            'partner_id', 'factory_id', 'create_time', 'update_time'
                        ], $arrInsert)->execute();
                    }
                }

                return true;
            });
        }

        return $isReturn;
    }
}