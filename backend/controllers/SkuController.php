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
<td><input type="hidden" name="SpuItemForm[sku][{$key}][id]" value="{$rst->id}">{$data['outer_color_value_value']}</td>
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
<td><input type="hidden" name="SpuItemForm[sku][{$key}][id]" value="{$rst->id}">{$data['outer_color_value_value']}</td>
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
}