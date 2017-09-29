<?php

use common\logic\AreaLogic;
use backend\widgets\MeTable;
use yii\helpers\Json;
use yii\helpers\Url;
use common\widgets\vue\asset\VueAsset;

/* @var $this yii\web\View */
/* @var $model \common\models\SkuItem */
VueAsset::register($this);

?>
<div class="store-index box box-primary">
    <div class="box-header with-border">
        <form class="form-inline">
            <div class="form-group">
                <label class="col-sm-3 control-label">提车门店:</label>
                <div class="col-sm-9">
                    <div id="cascade">
                        <el-cascader
                                :options="options"
                                filterable
                                v-model="selectedOptions"
                                clearable
                                @change="handleChange"
                                change-on-select
                        >
                        </el-cascader>
                        <input type="hidden" id="strAddressId"/>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success" id="show-store">添加</button>
        </form>
    </div>
    <div class="box-body">
        <?= MeTable::widget(['table' => ['class' => 'table table-bordered table-hover',], 'buttonsTemplate' => '',]) ?>
    </div>
</div>
<!-- 弹出modal -->
<div class="modal fade " id="store-info" tabindex="-1" role="dialog"></div>

<?php $this->beginBlock('javascript'); ?>
<script>
    var objVue = null;
    var m = meTables({
        title: "门店管理",
        searchType: "top",
        search: {render: false},
        bCheckbox: false,
        "bEvent": false,
        url: {
            "search": "<?=Url::toRoute(['store/get-store', 'id' => $model->id])?>"
        },
        operations: {
            "isOpen": false
        },
        table: {
            "bLengthChange": false,
            "iDisplayLength": 20,
            "order": [],
            "aoColumns": [
                {
                    "title": "门店名称",
                    "data": "name",
                    "sName": "name",
                    "bSortable": false
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
                    "title": "操作",
                    "data": "id",
                    "sName": "id",
                    "bSortable": false,
                    "render": function (id) {
                        return "<button type=\"button\" class=\"btn btn-warning btn-xs btn-delete-store\" data=" + id + ">取消选择</button>";
                    }
                }
            ]
        }
    });

    $(function () {
        m.init();

        // 最后使用vue
        objVue = new Vue({
            el: '#cascade',
            data: function () {
                return {
                    options: <?=Json::encode(AreaLogic::instance()->getAreaTree(2))?>,
                    selectedOptions: []
                };
            },
            methods: {
                handleChange: function (value) {
                    $("#strAddressId").val(value.join(","));
                }
            }
        });

        // 选择可用门店
        $("#show-store").click(function () {
            $("#store-info").load("<?=Url::toRoute(['store/get-select-store', 'id' => $model->id, 'partner_id' => $model->partner_id,])?>&strAddressId=" + $("#strAddressId").val()).modal();
        });

        // 点击确定数据
        $(document).on("click", "#me-table-store-save-user", function () {
            $.ajax({
                url: "<?=Url::toRoute(['store/update-store'])?>",
                type: "POST",
                data: {
                    "id": <?=$model->id?>,
                    "store_id": $("#select-store-id").val()
                },
                dataType: "json"
            }).done(function (json) {
                layer.msg(json.errMsg, {icon: json.errCode === 0 ? 6 : 5});
                if (json.errCode === 0) {
                    m.search();
                    $("#store-info").modal("hide");
                }
            }).fail(function () {
                layer.msg("服务器繁忙,请稍候再试...", {icon: 5});
            });
        });

        // 取消选择
        $(document).on("click", ".btn-delete-store", function () {
            var id = $(this).attr("data");
            if (id) {
                layer.confirm("确定需要取消选择吗?", {
                    title: "温馨提醒",
                    icon: 0
                }, function(){
                    $.ajax({
                        url: "<?=Url::toRoute(['store/delete-spu-item-store'])?>",
                        type: "POST",
                        data: {
                            "id": id
                        },
                        dataType: "json"
                    }).done(function (json) {
                        layer.msg(json.errMsg, {icon: json.errCode === 0 ? 6 : 5});
                        if (json.errCode === 0) {
                            m.search();
                        }
                    }).fail(function () {
                        layer.msg("服务器繁忙,请稍候再试...", {icon: 5});
                    });
                });

                return false;
            }
        })
    });
</script>
<?php $this->endBlock(); ?>
