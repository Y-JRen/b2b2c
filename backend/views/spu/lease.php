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
                        $html = Html::a('编辑', ['update', 'id' => $data->id]);
                        $html .= Html::a('上架', ['update', 'id' => $data->id], ['style' => 'padding:0 5px;']);
                        return $html;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>

<?php

$script = <<<_SCRIPT
    var add_form = '<tr><td><input class="form-control"></td><td><input class="form-control"></td><td><input class="form-control"></td><td><input class="form-control"></td><td><input class="form-control"></td><td><input class="form-control"></td><td><input class="form-control"></td><td></td></tr>'
    $('.lease_add').click(function(){
        if($('#lease_add tbody').find('.empty').length) {
            $('#lease_add tbody').html(add_form);
        } else {
            $('#lease_add tbody').append(add_form);
        }
    });
_SCRIPT;


$this->registerJs($script);
?>

