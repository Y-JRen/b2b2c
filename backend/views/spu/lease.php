<?php
/**
 * Created by PhpStorm.
 * User: xiongjun
 * Date: 2017/10/10
 * Time: 11:35
 */

use yii\helpers\Html;
?>
<div>
    <div class="box-header with-border">
        <?= Html::a('添加方案', 'javascript:void(0)', ['class' => 'btn btn-default lease_add']) ?>
    </div>
    <div class="row content" id="lease_add">
        
        <?= \common\widgets\GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                [
                    'label' => '首付金额（元)',
                    'value' => function($model) {
                        return $model->down_payment;
                    }
                ],
                [
                    'label' => '直租租期（月）',
                    'value' => function($model) {
                        return $model->month_period;
                    }
                    
                ],
                [
                    'label' => '每月租金（元）',
                    'value' => function($model) {
                        return $model->month_lease_fee;
                    }
                ],
                [
                    'label' => '尾款金额（元）',
                    'value' => function($model) {
                        return $model->tail_fee;
                    }
                ],
                [
                    'label' => '尾款分期期数（月）',
                    'value' => function($model) {
                        return $model->tail_pay_period;
                    }
                ],
                [
                    'label' => '尾款月供金额（元）',
                    'value' => function($model) {
                        return $model->tail_month_pay_fee;
                    }
                ],
                [
                    'label' => '服务费',
                    'value' => function($model) {
                        return $model->service_charge;
                    }
                ],
                [
                    'label' => '操作',
                    'format' => 'raw',
                    'value' => function($data) {
                        $html = Html::a('删除', 'javascript:void(0)', ['class' => 'lease_delete', 'title' => $data->id]);
                        return $html;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>

<?php
$html = <<<_HTML
<tr><td><input name="down_payment" class="form-control"></td><td><input name="month_period" class="form-control"></td><td><input name="month_lease_fee" class="form-control"></td><td><input name="tail_fee" class="form-control"></td><td><input name="tail_pay_period" class="form-control"></td><td><input name="tail_month_pay_fee" class="form-control"></td><td><input name="service_charge" class="form-control"></td><td><a class="lease_save" href="javascript:void(0)">保存</a></td></tr>
_HTML;

$script = <<<_SCRIPT
    $('.lease_add').click(function(){
        if($('#lease_add tbody').find('.empty').length) {
            $('#lease_add tbody').html('{$html}');
        } else {
            $('#lease_add tbody').append('{$html}');
        }
    });
    $("body").delegate('.lease_save', "click",function(){
        var parent = $(this).parent().parent();
        var down_payment = parent.find('input[name="down_payment"]').val();
        var month_period = parent.find('input[name="month_period"]').val();
        var month_lease_fee = parent.find('input[name="month_lease_fee"]').val();
        var tail_fee = parent.find('input[name="tail_fee"]').val();
        var tail_pay_period = parent.find('input[name="tail_pay_period"]').val();
        var tail_month_pay_fee = parent.find('input[name="tail_month_pay_fee"]').val();
        var service_charge = parent.find('input[name="service_charge"]').val();
        var skuId = {$skuId}
        if(!isFloat(down_payment) || !isFloat(month_period) || !isFloat(month_lease_fee) || !isFloat(tail_fee)
        || !isFloat(tail_pay_period) || !isFloat(tail_month_pay_fee) || !isFloat(service_charge)) {
            layer.msg('格式错误！');
            return false;
        }
        $.ajax({
            url: "/sku/add-lease",
            method: 'post',
            data: {down_payment:down_payment,month_period:month_period,month_lease_fee:month_lease_fee,tail_fee:tail_fee,
            tail_pay_period:tail_pay_period,tail_month_pay_fee:tail_month_pay_fee,service_charge:service_charge,sku_id:skuId},
            dataType:'json',
            success: function(data){
                if(data.isSuccess) {
                    parent.html(data.data);
                } else {
                    layer.msg('添加失败');
                }
            }
        });
    });
    function isFloat(oNum){
        if(!oNum) return false;
        var strP=/^\d+(\.\d+)?$/;
        if(!strP.test(oNum)) return false;
        try{
            if(parseFloat(oNum)!=oNum) return false;
        }catch(ex){
            return false;
        }
        return true;
    }
    $('.lease_delete').click(function(){
        id = $(this).attr('title');
        var parent = $(this).parent().parent()
        $.post('financial-lease-delete',{id:id},function(data){
            if(data.isSuccess){
                parent.remove();
            } else {
                layer.msg(data.data);
            }
        },'json');
    });
_SCRIPT;


$this->registerJs($script);
?>

