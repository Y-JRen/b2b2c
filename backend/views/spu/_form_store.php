<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use common\logic\AreaLogic;

$store_model = new \common\models\Store();
?>
<div class="box-body row">
    <label class="col-md-1 control-label">提车门店</label>
    <div class="col-md-2">
        <?=
        Html::activeDropDownList(
            $store_model,
            'province_code',
            AreaLogic::instance()->getChildrenByParentCode('000000'),
            [
                'prompt'=>'--请选择省--',
                'onchange'=>'
                        $("select#store-id").html("<option value=\'empty\'>--请选择门店--</option>");
                        $.post("'.yii::$app->urlManager->createUrl('area/get-child').'?typeid=1&code="+$(this).val(),function(data){
                            $("select#store-city_code").html(data);
                        });',
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-md-2">
            <?php
        echo Html::activeDropDownList(
            $store_model,
            'city_code',
            AreaLogic::instance()->getChildrenByParentCode($store_model->province_code),
            [
                'prompt'=>'--请选择市--',
                'onchange'=>'
                    //$(".form-group.field-store-id").show();
                    $.post("'.yii::$app->urlManager->createUrl('store/get-store-by-area').'?area_code="+$(this).val(),function(data){
                        $("select#store-id").html(data);
                    });',
                'class' => 'form-control'
            ]);
            ?>
            </div>
            <div class="col-md-2">
                <?php
                echo Html::activeDropDownList(
            $store_model,
            'id',
            \common\logic\StoreLogic::instance()->getStoreByArea($store_model->city_code),
            [
                'prompt'=>'--请选择门店--',
                'class' => 'form-control'
            ]);?>
                </div>
                <div class="col-md-5">
                    <?php
                echo Html::button('添加', [
            'class' => 'btn btn-success btn-flat',
            'onclick' => '
                if($("select#store-id").val() <= 0){
                    layer.alert("没有选择门店");
                    return;
                }else{
                    $.post("'.yii::$app->urlManager->createUrl('store/update-store').'",{id:'.$model->id.',store_id:$("select#store-id").val()},function(data){
                        if(data.errCode == 0){
                            loadStore();
                        }else{
                            layer.msg(data.errMsg);
                        }
                    });
                }'
        ]);
        ?>
    </div>
</div>


