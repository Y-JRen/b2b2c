<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/10/11
 * Time: 10:22
 */

namespace backend\controllers;


use backend\models\form\SpuItemForm;
use common\logic\SkuLogic;
use common\models\SkuFinancialLease;
use common\models\SkuSku;
use yii\helpers\Html;

/**
 * Sku
 *
 * Class SkuController
 * @package backend\controllers
 */
class SkuController extends BaseController
{
    /**
     * 新增SKU
     */
    public function actionAdd()
    {
        if (\Yii::$app->request->isAjax) {
            $itemId = \Yii::$app->request->post('item_id');
            $key = \Yii::$app->request->post('key');
            if (!$itemId) {
                $this->returnJson('参数错误', 0);
            }
            $data = \Yii::$app->request->post('data');
            $spu = SpuItemForm::findOne($itemId);
            $rst = SkuLogic::instance()->addSku($spu, $data);
            $del = Html::a('删除', ['delete-sku', 'skuId' => $rst->id, 'id' => $itemId]);
            if ($rst) {
                if($spu->item_type_id == 1) {
                $html = <<<_HTML
<tr>
<td><input class="sku_id" type="hidden" name="SpuItemForm[sku][{$key}][id]" value="{$rst->id}">{$data['outer_color_value_value']}</td>
<td>{$data['inner_color_value_value']}</td>
<td><input name="SpuItemForm[sku][{$key}][price]" value="{$rst->price}" class="form-control sku_price"></td>
<td><input name="SpuItemForm[sku][{$key}][name]" value="{$rst->name}" class="form-control sku_name"></td>
<td><input name="SpuItemForm[sku][{$key}][subname]" class="form-control"></td>
<td>{$del}</td>
</tr>
_HTML;
                } else {
                    $html = <<<_HTML
<tr>
<td><input class="sku_id" type="hidden" name="SpuItemForm[sku][{$key}][id]" value="{$rst->id}">{$data['outer_color_value_value']}</td>
<td>{$data['inner_color_value_value']}</td>
<td><input name="SpuItemForm[sku][{$key}][price]" value="{$rst->price}" class="form-control sku_price"></td>
<td><input name="SpuItemForm[sku][{$key}][name]" value="{$rst->name}" class="form-control sku_name"></td>
<td><input name="SpuItemForm[sku][{$key}][subname]" class="form-control"></td>
<td><a href="javascript:void(0)" class="add_lease">编辑</a></td>
<td>{$del}</td>
</tr>
_HTML;
                }
                $this->returnJson($html);
            }
        }
    }
    
    /**
     * @TODO create_person
     * 添加金融方案
     */
    public function actionAddLease()
    {
        if (\Yii::$app->request->isAjax) {
            $sku = SkuSku::findOne(\Yii::$app->request->post('sku_id'));
            $data = \Yii::$app->request->post();
            $data['partner_id'] = $sku->partner_id;
            $data['spu_id'] = $sku->spu_id;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['create_person'] = 'test';
            $lease = new SkuFinancialLease();
            if ($lease->load(['SkuFinancialLease' => $data]) && $lease->save()) {
                $del = Html::a('删除', ['/spu/financial-lease-delete', 'id' => $data->id]);
                $html = <<<_HTML
<td>{$lease->down_payment}</td>
<td>{$lease->month_period}</td>
<td>{$lease->month_lease_fee}</td>
<td>{$lease->tail_fee}</td>
<td>{$lease->tail_pay_period}</td>
<td>{$lease->tail_month_pay_fee}</td>
<td>{$lease->service_charge}</td>
<td>{$del}</td>
_HTML;
                $this->returnJson($html);
            } else {
                $this->returnJson('保存失败！', 0);
            }
        }
        $this->returnJson('请求错误', 0);
    }
}