<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use yii\helpers\Url;
use common\logic\CarLogic;
use kartik\select2\Select2;
use common\models\DealerForm;



$faceModel = new DealerForm();


/* @var $this yii\web\View */
/* @var $model common\models\Partner */
/* @var $form common\widgets\ActiveForm */

$intPartnerId = $model->id;
$faceModel->setPartnerId($intPartnerId);
?>

<div class="partner-form">
    <?php $form = ActiveForm::begin([
        'id' => 'dealer-form-update'
    ]); ?>
    <div class="box-body">
        <?=$form->field($faceModel, 'partner_id')->hiddenInput()->label('', ['class' => 'hide'])?>
        <?= $form->field($faceModel, 'dealer')->widget(
            Select2::className(),
            [
                'options' => [
                        'multiple' => true, 'placeholder' => '请选择 ...'
                ],
                'data' => CarLogic::instance()->getFactoryMenu(),
                'maintainOrder' => true,
                'name' => 'partner_identity[]',
                'pluginOptions' => [
                    'tags' => true,
                    'maximumInputLength' => 10
                ]
            ]
        ) ?>

        <div class="form-group">
            <div class="row">
                <label class="control-label col-lg-1" for="store_id_info">可用门店</label>
                <div class="col-lg-10">
                    <table class="table table-bordered table-hover" id="me-store-table"></table>
                    <button type="button" class="btn btn-info btn-flat" id="show-store">选择可用门店</button>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="button" class="btn btn-success btn-flat" id="submit-dealer">确定</button>
        <?= Html::a('取消', ['partner/index'], ['class' => 'btn btn-default btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<!-- 弹出modal -->
<div class="modal fade " id="store-info" tabindex="-1" role="dialog"></div>

<?php $this->beginBlock('javascript-dealer') ?>
<script>
    var mStore = $("#me-store-table").DataTable({
        "bPaginate": false,             // 不使用分页
        "bLengthChange": false,
        "iDisplayLength": 20,
        "order": [],
        searching: false,
        "bAutoWidth": false,           	// 是否自动计算列宽
        ajax: {
            url: "<?=Url::toRoute(['partner/get-store', 'id' => $intPartnerId])?>",
            type: "GET",
            dataType: "json",
            data: function(d) {
                return d;
            }
        },
        "aoColumns": [
            {
                "title": "门店名称",
                "data": "name",
                "sName": "name",
                "bSortable": false,
                "edit": {"type": "text", "required": true, "rangelength": "[2, 255]"}
            },
            {
                "title": "所在地区",
                "data": "province_name",
                "sName": "province_name",
                "bSortable": false,
                "render": function(data, isDisplay, array) {
                    return data + " " + array["city_name"] + " " + array["area_name"];
                }
            },
            {
                "title": "门店地址",
                "data": "address",
                "sName": "address",
                "bSortable": false
            },
            {
                "title": "联系人",
                "data": "contact_person",
                "sName": "contact_person",
                "bSortable": false
            },
            {
                "title": "联系电话",
                "data": "contact_phone",
                "sName": "contact_phone",
                "bSortable": false
            },
            {
                "title": "是否自营门店",
                "data": "is_partner_self",
                "sName": "is_partner_self",
                "bSortable": false,
                "render": function(value) {
                    return parseInt(value) === 1 ? "是" : "否"
                }
            }
        ]
    });


    $("#me-store-table_info").hide();

    // 选择可用门店
    $("#show-store").click(function(){
        $("#store-info").load("<?=Url::toRoute(['partner/get-select-store', 'id' => $intPartnerId])?>").modal();
    });

    // 确定
    $(document).on("click", "#me-table-store-save-user", function(){
        var id = $("#select-store-id").val();
        if (id) {
            $.ajax({
                url: "<?=Url::toRoute(['partner/create-store'])?>",
                data: {
                    store_id: id,
                    partner_id: "<?=$intPartnerId?>"
                },
                type: "POST",
                dataType: "json"
            }).done(function(json) {
                layer.msg(json.errMsg, {icon: json.errCode === 0 ? 6 : 5});
                if (json.errCode === 0) {
                    // 处理显示数据
                    mStore.ajax.reload(); // search();
                    $("#store-info").modal("hide");
                }
            }).fail(function(){
                layer.msg("服务器繁忙,请稍候再试...", {icon: 5});
            });
        } else {
            layer.msg("没有选择门店...", {icon: 5});
        }
    });

    // 点击确定数据
    $("#submit-dealer").click(function(){
        $.ajax({
            url: "<?=Url::toRoute(['partner/update-dealer'])?>",
            type: "POST",
            data: $("#dealer-form-update").serialize(),
            dataType: "json"
        }).done(function(json) {
            layer.msg(json.errMsg, {icon: json.errCode === 0 ? 6 : 5});
            if (json.errCode === 0) {

            }
        }).fail(function(){
            layer.msg("服务器繁忙,请稍候再试...", {icon: 5});
        });
    });
</script>
<?php $this->endBlock(); ?>
