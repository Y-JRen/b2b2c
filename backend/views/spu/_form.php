<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\form\SpuForm */
/* @var $form common\widgets\ActiveForm */
?>

<div class="spu-form-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'partner_id')->widget(\kartik\select2\Select2::className(),[
            'data' => \common\logic\PartnerLogic::instance()->getPartnerMenu(),
            'options' => ['multiple' => false, 'placeholder' => '请选择'],
            'maintainOrder' => true,
            'pluginOptions' => [
                'allowClear' => true,
                'tags' => true,
            ],

        ]) ?>
        <?= $form->field($model, 'category')->dropDownList([
            1 => '中规车',
        ]) ?>
        <?= $form->field($model, 'brand_id')->dropDownList(
                \common\logic\CarLogic::instance()->getBrandMenu()
        ) ?>
        <?= $form->field($model, 'factory_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\common\logic\CarLogic::instance()->geFactoryByBrandId($model->brand_id),
                'key', 'value'
                )
        ) ?>
        <?= $form->field($model, 'series_id')->dropDownList(
            \yii\helpers\ArrayHelper::map(\common\logic\CarLogic::instance()->getSeriesByFactoryId($model->factory_id),
                'key', 'value'
            )
        ) ?>
        <?= $form->field($model, 'car_id')->dropDownList(
            \yii\helpers\ArrayHelper::map(\common\logic\CarLogic::instance()->getCarBySeriesId($model->series_id),
                'key', 'value'
            )
        ) ?>

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
    
    
_SCRIPT;

$this->registerJs($script);