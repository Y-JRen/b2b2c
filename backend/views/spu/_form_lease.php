<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\form\SpuForm */
/* @var $form common\widgets\ActiveForm */


$color = \common\logic\SpuLogic::instance()->getSpuColorSelect($model->spu_id);
$guidePrice = \common\models\CarBrandSonTypeInfo::findOne($model->car_id)->factory_price * 10000;
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">基本信息</a></li>
        <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">商品介绍</a></li>
        <li class=""><a href="#settings" data-toggle="tab" aria-expanded="false">提车地点</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="activity">
            <div class="spu-form-form">
                <?php $form = ActiveForm::begin(); ?>
                <div class="box-body table-responsive">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="inputName" class="col-sm-3 control-label">所属商户:</label>

                            <div class="col-sm-9">
                                <p class="form-control" style="border: none">名字</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="inputName" class="col-sm-3 control-label">所属ID:</label>

                            <div class="col-sm-9">
                                <p class="form-control" style="border: none"><?=$model->partner_id?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="inputName" class="col-sm-3 control-label">商品类型:</label>

                            <div class="col-sm-9">
                                <p class="form-control" style="border: none">
                                    <?= \common\models\SkuSpuType::findOne($model->type_id)->name ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-md-2">
                            <p class="form-control" style="background: #d3d3d3"><?=$model->spuType->name?></p>
                        </div>
                        <div class="form-group col-md-2">
                            <p class="form-control" style="background: #d3d3d3"><?=$model->brand_name?></p>
                        </div>
                        <div class="form-group col-md-2">
                            <p class="form-control" style="background: #d3d3d3"><?=$model->factory_name?></p>
                        </div>
                        <div class="form-group col-md-2">
                            <p class="form-control" style="background: #d3d3d3"><?=$model->series_name?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <p class="form-control" style="background: #d3d3d3"><?=$model->name?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="inputName" class="col-sm-3 control-label">指导报价(元):</label>

                            <div class="col-sm-9">
                                <p id="guide_price" title="" class="form-control" style="border: none">
                                    <?=  $guidePrice?>
                                </p>
                            </div>
                        </div>
                    </div>
                    

                    <div class="form-group field-spuitemform-color required">
                        <div class="row">
                            <label class="control-label col-sm-1" for="spuitemform-color">外观颜色</label>
                            <div class="col-sm-4">
                                <input type="hidden" id="spuitemform-color" class="form-control" name="SpuItemForm[sku]" value="<?=count($model->sku) ? : 0;?>" aria-required="true">
                                <?php foreach($color as $value): ?>
                                <div class="col-sm-5" id="<?=($value->name == "内色") ? 'inner' : 'outer' ;?>_color">
                                    <input type="hidden" value="<?=$value->id?>" class="label_id">
                                    <input type="hidden" value="<?=$value->name?>" class="label_name">
                                    <select class="form-control" aria-required="true">
                                        <option>请选择<?=$value->name?></option>
                                            <?php foreach ($value->value as $v) :?>
                                                <option value="<?=$v->id?>"><?=$v->name?></option>
                                            <?php endforeach;?>
                                    </select>
                                </div>
                                <?php endforeach;?>
                                <div class="col-sm-2">
                                    <a id="add_color" href="javascript:void(0)" class="btn btn-primary">添加</a>
                                </div>
                            </div>
                            <br><br>
                            <br><br>
                            <div class="col-sm-offset-1 col-sm-8">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <th>外观颜色</th>
                                        <th>内饰颜色</th>
                                        <th>售价（元）（默认指导价）</th>
                                        <th>商品标题(必填)</th>
                                        <th>自定义标题</th>
                                        <th>操作</th>
                                    </tr>
                                    </tbody>
                                    <tbody id="sku">
                                        <?php foreach ($model->sku as $k => $sku):?>
                                            <tr>
                                                <?php $param = \common\logic\SpuLogic::instance()->getParameter($sku->id)?>
                                                <input type="hidden" name="SpuItemForm[sku][<?=$k?>][id]" value="<?=$sku->id?>">
                                                <td><?=$param['外色']?></td>
                                                <td><?=$param['内色']?></td>
                                                <td><input class="form-control" name="SpuItemForm[sku][<?=$k?>][price]" value="<?=$sku->price?>"></td>
                                                <td><input class="form-control" name="SpuItemForm[sku][<?=$k?>][name]" value="<?=$sku->name?>"></td>
                                                <td><input class="form-control" name="SpuItemForm[sku][<?=$k?>][subname]" value="<?=$sku->subname?>"></td>
                                                <td><?=Html::a('删除', ['delete-sku', 'skuId' => $sku->id, 'id' => $model->id])?></td>
                                            </tr>
                                        <?php  endforeach;?>
                                    </tbody>
                                </table>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>

                    <?= $form->field($model, 'item_financial')->widget(
                            \kartik\select2\Select2::className(),
                            [
                                'options' => ['multiple' => true, 'placeholder' => '请选择'],
                                'data' => \common\logic\FinancialLogic::instance()->getPartnerFinancial(1)
                            ]
                    )->label('金融方案') ?>

                    <?= $form->field($model, 'deposit')->textInput()->label('定金(元)') ?>
                    
                </div>
                <div class="box-footer">
                    <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        
        </div>
    </div>
</div>

<?php

$script = <<<_SCRIPT

    $(".nav-tabs li").click(function(){
    });
    function changeValue(current, next, url) {
        
        if(current == "spuform-brand_id"){
            $("#"+next).html('<option>厂商</option>')
            $("#spuform-series_id").html('<option>车系</option>')
            $("#spuform-car_id").html('<option>车型</option>')
        } else if (current == "spuform-factory_id") {
            $("#spuform-series_id").html('<option>车系</option>')
            $("#spuform-car_idd").html('<option>车型</option>')
        } else {
            $("#spuform-car_id").html('<option>车型</option>')
        }
        if(next == "spuform-factory_id") {
            var html = '<option>厂商</option>';
        } else if(next == "spuform-series_id") {
            var html = '<option>车系</option>';
        } else {
            var html = '<option>车型</option>';
        }
        $.get(url, function(data){
            for (var i = 0; i < data.length; i++){
                html += "<option value="+data[i]['key']+">"+data[i]['value']+"</option>"
            }
            $("#"+next).html(html)
        },"json");
    }
    $("#spuform-brand_id").change(function(){
        changeValue("spuform-brand_id", 'spuform-factory_id', "/car/brand?brand_id="+$(this).val())
    });
    $("#spuform-factory_id").change(function(){
        changeValue("spuform-factory_id", 'spuform-series_id', "/car/series?factory_id="+$(this).val())
    });
    $("#spuform-series_id").change(function(){
        changeValue("spuForm-series_id", 'spuform-car_id', "/car/car?car_type_id="+$(this).val())
    });
    
    $("#add_color").click(function(){
        var inner_color_label_id = $('#inner_color .label_id').val()
        var inner_color_label_value = $('#inner_color .label_name').val()
        var inner_color_value_id = $('#inner_color select option:selected').attr('value')
        var inner_color_value_value = $('#inner_color select option:selected').text()
        
        var outer_color_label_id = $('#outer_color .label_id').val()
        var outer_color_label_value = $('#outer_color .label_name').val()
        var outer_color_value_id = $('#outer_color option:selected').attr('value')
        var outer_color_value_value = $('#outer_color option:selected').text()
        
        if(!inner_color_value_id) {
            layer.msg("请选择外观颜色！");
            return false;
        }
        if(!outer_color_value_id) {
            layer.msg("请选择外观颜色！");
            return false;
        }
        skuVal = $("input[name='SpuItemForm[sku]']").val();
        $("input[name='SpuItemForm[sku]']").val(skuVal++);
        var html = '<tr>';
        html += '<td><input name="SpuItemForm[sku]['+skuVal+'][outer_color_label_id]" value="'+outer_color_label_id+'" type="hidden"><input name="SpuItemForm[sku]['+skuVal+'][outer_color_label_value]" value="'+outer_color_label_value+'" type="hidden"><input name="SpuItemForm[sku]['+skuVal+'][outer_color_value_id]" value="'+outer_color_value_id+'" type="hidden"><input name="SpuItemForm[sku]['+skuVal+'][outer_color_value_value]" value="'+outer_color_value_value+'" type="hidden">'+outer_color_value_value+ '</td>';
        html += '<td><input name="SpuItemForm[sku]['+skuVal+'][inner_color_label_id]" value="'+inner_color_label_id+ '" type="hidden"><input name="SpuItemForm[sku]['+skuVal+'][inner_color_label_value]" value="'+inner_color_label_value+ '" type="hidden"><input name="SpuItemForm[sku]['+skuVal+'][inner_color_value_id]" value="'+inner_color_value_id+ '" type="hidden"><input name="SpuItemForm[sku]['+skuVal+'][inner_color_value_value]" value="'+inner_color_value_value+ '" type="hidden">'+inner_color_value_value+'</td>';
        html +=  '<td><input name="SpuItemForm[sku]['+skuVal+'][price]" value="{$guidePrice}" class="form-control sku_price"></td>';
        html +=  '<td><input name="SpuItemForm[sku]['+skuVal+'][name]" class="form-control sku_name"></td>';
        html +=  '<td><input name="SpuItemForm[sku]['+skuVal+'][subname]" class="form-control"></td>';
        html += '<td><a href="javascript:void(0)" class="del_sku">删除</a></td></tr>';
        
       
        $("#sku").append(html);
       
    });
    
    $("body").delegate('.del_sku', "click",function(){
        skuVal = $("input[name='SpuItemForm[sku]']").val();
        $("input[name='SpuItemForm[sku]']").val(skuVal--)
        $(this).parent().parent().remove()
    });
    
    $(document).ready(
    $('#w0').on('beforeSubmit', function(event, jqXHR, settings) {
        $('.field-spuitemform-color input').blur(function(){
             $('.field-spuitemform-color').removeClass('has-error');
              $(".sku_name").removeClass('has-error');
            $('.field-spuitemform-color').find('.help-block').html('');
        });
        $(".sku_name").each(function(){
            $(this).parent().removeClass('has-error');
            $('.field-spuitemform-color').find('.help-block').html('');
            if(!$(this).val()){
                $(this).parent().addClass('has-error');
                $('.field-spuitemform-color').find('.help-block').html('<div style="color: red;">商品标题必填</div>');
            }
        });
        if(!$("input[name='SpuItemForm[sku]']").val()) {
            $('.field-spuitemform-color').removeClass('has-error');
            $('.field-spuitemform-color').find('.help-block').html('');
            if(!$(this).val()){
                $('.field-spuitemform-color').addClass('has-error');
                $('.field-spuitemform-color').find('.help-block').html('外观颜色必选')
            }
        }
        var form = $(this);
        if(form.find('.has-error').length) {
            return false;
        }

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function(data) {
                // do something ...
            }
        });

        return false;
    }),
);


_SCRIPT;

$this->registerJs($script);?>