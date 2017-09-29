<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\form\SpuForm */
/* @var $form common\widgets\ActiveForm */


$color = json_decode(\common\logic\CarLogic::instance()->getColorByCarId($model->car_id), true);
$guidePrice = \common\models\CarBrandSonTypeInfo::findOne($model->car_id)->factory_price * 10000;
?>

<div class="col-md-12">
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
                                <p class="form-control" style="background: #d3d3d3"><?=$model->category_name?></p>
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
                                    <input type="hidden" id="spuitemform-color" class="form-control" name="SpuItemForm[sku]" aria-required="true">
                                    <div class="col-sm-5">
                                        <select id="outer_color" class="form-control" aria-required="true">
                                            <option>请选择外色</option>
                                            <?php if(!empty($color[0])): ?>
                                            <?php foreach($color[0] as $value): ?>
                                                <option value="<?=$value['value']?>"><?=$value['label']?></option>
                                            <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </div>
                                    <div class="col-sm-5">
                                        <select id="inner_color" class="form-control" aria-required="true">
                                            <option>请选择内色</option>
                                            <?php if(!empty($color[1])): ?>
                                            <?php foreach ($color[1] as $value): ?>
                                                <option value="<?=$value['value']?>"><?=$value['label']?></option>
                                            <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </div>
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
                                            <th>商品副标题</th>
                                        </tr>
                                        </tbody>
                                        <tbody id="sku">
                                        
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

<?php

$script = <<<_SCRIPT
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
        var inner_color_label = $('#inner_color option:selected').text()
        var inner_color_value = $('#inner_color option:selected').attr('value')
        
        var outer_color_label = $('#outer_color option:selected').text()
        var outer_color_value = $('#outer_color option:selected').attr('value')
        
        console.log(inner_color_value, outer_color_value);
        if(!inner_color_value) {
            layer.msg("请选择外观颜色内色！");
            return false;
        }
        if(!outer_color_value) {
            layer.msg("请选择外观颜色外色！");
            return false;
        }
        skuVal = $("input[name='SpuItemForm[sku]']").val();
        $("input[name='SpuItemForm[sku]']").val(skuVal++);
        var html = '<tr>';
        html += '<td><input name="SpuItemForm[sku]['+skuVal+'][outer_color]" value="'+outer_color_value+'" type="hidden">'+outer_color_label+ '</td>';
        html += '<td><input name="SpuItemForm[sku]['+skuVal+'][inner_color]" value="'+inner_color_value+ '" type="hidden">'+inner_color_label+'</td>';
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
            
            </div>
        </div>
    </div>
</div>
