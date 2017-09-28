<?php

use common\logic\AreaLogic;
use yii\helpers\Html;
use backend\widgets\MeTable;
use yii\helpers\Json;
use yii\helpers\Url;
use common\widgets\vue\asset\VueAsset;

/* @var $this yii\web\View */
VueAsset::register($this);

?>

<div class="box-header with-border">
    <?= Html::a('新增门店', '#', ['class' => 'btn btn-success btn-flat', 'onclick' => 'mInfo.create()']) ?>
</div>
<div class="box-body">
    <?=MeTable::widget([
        'table' => [
            'class' => 'table table-bordered table-hover',
        ],
        'buttonsTemplate' => '',
    ])?>
</div>

<?php $this->beginBlock('javascript-store'); ?>
<script>
    function handleEmpty(td, data) {
        $(td).html(data ? data : '--');
    }

    var objVue = null,
        arrPartner = <?=\yii\helpers\Json::encode($partner)?>;

    mt.extend({
        // vue 按钮
        "vueInputCreate": function(input) {
            var html = '';
            for(var i in input.attributes) {
                html += this.inputCreate({
                    type: "hidden",
                    id: input.attributes[i],
                    name: input.attributes[i]
                })
            }
            return html + '<div id="cascade">' +
                '<el-cascader ' +
                ':options="options" ' +
                'filterable ' +
                'v-model="selectedOptions" ' +
                'clearable @change="handleChange" >' +
                '</el-cascader>' +
                '</div>';
        },

        // 获取地址信息
        'inputButtonCreate': function(input) {
            input.type = "text";
            var html = this.inputCreate(input);
            html += "<button type=\"button\" id=\"get-address\" class=\"btn btn-info\" style=\"margin-top:15px\">获取地址经纬度</button>";
            return html;
        }
    });

    mt.fn.extend({
        "beforeShow": function(data) {
            // 清理vue
            objVue.$children[0].handlePick([], true);
            if (this.action === "update") {
                objVue.selectedOptions.push(data["province_code"]);
                objVue.selectedOptions.push(data["city_code"]);
                objVue.selectedOptions.push(data["area_code"]);
            }

            return true;
        },
        "afterSave": function() {
            if (mStore) mStore.ajax.reload();
            return true;
        }
    });

    var mInfo = meTables({
        title: "门店管理",
        searchType: "top",
        search: {render: false},
        bCheckbox: false,
        "bEvent": false,
        params: {
            // 合作商
            "partner_id": <?=$model->id?>
        },
        operations: {
            buttons: {
                "see": {"bShow": false},
                "delete": {"bShow": false}
            }
        },
        url: {
            "search": "<?=Url::toRoute(['store/search'])?>",
            "create": "<?=Url::toRoute(['store/create'])?>",
            "update": "<?=Url::toRoute(['store/update'])?>",
            "delete": "<?=Url::toRoute(['store/delete'])?>"
        },
        table: {
            "bLengthChange": false,
            "iDisplayLength": 20,
            "order": [],
            "aoColumns": [
                {
                    "isHide": true,
                    "title": "ID",
                    "data": "id",
                    "sName": "id",
                    "bSortable": false,
                    "edit": {"type": "hidden"}
                },
                {
                    "isHide": true,
                    "title": "合作商",
                    "data": "partner_id",
                    "sName": "partner_id",
                    "bSortable": false,
                    value: arrPartner,
                    "render": function(data) {
                        return arrPartner[data] ? arrPartner[data] : data;
                    },
                    "edit": {"type": "select", "required": true, "number": true}
                },
                {
                    "title": "门店名称",
                    "data": "name",
                    "sName": "name",
                    "bSortable": false,
                    "createdCell": handleEmpty,
                    "edit": {"type": "text", "required": true, "rangelength": "[2, 255]"}
                },
                {
                    "title": "所在地区",
                    "data": "province_name",
                    "sName": "province_name",
                    "bSortable": false,
                    "edit": {
                        "type": "vueInput",
                        "attributes": ['province_code', 'city_code', 'area_code', 'province_name', 'city_name', 'area_name']
                    }
                },
                {
                    "title": "门店地址",
                    "data": "address",
                    "sName": "address",
                    "bSortable": false,
                    "edit": {"type": "inputButton", "required": true, "rangelength": "[2, 255]", "id": "store-address"}
                },
                {
                    "isHide": true,
                    "title": "经度",
                    "data": "lon",
                    "sName": "lon",
                    "bSortable": false,
                    "edit": {"type": "text", "required": true, "number": true, "id": "store-lon"}
                },
                {
                    "isHide": true,
                    "title": "纬度",
                    "data": "lat",
                    "sName": "lat",
                    "bSortable": false,
                    "edit": {"type": "text", "required": true, "number": true, "id": "store-lat"}
                },
                {
                    "title": "联系人",
                    "data": "contact_person",
                    "sName": "contact_person",
                    "bSortable": false,
                    "edit": {"type": "text", "required": true, "rangelength": "[2, 255]"}
                },
                {
                    "title": "联系电话",
                    "data": "contact_phone",
                    "sName": "contact_phone",
                    "bSortable": false,
                    "edit": {"type": "text", "required": true, "rangelength": "[2, 255]"}
                },
                {
                    "isHide": true,
                    "title": "状态",
                    "data": "status",
                    "sName": "status",
                    "bSortable": false,
                    "value": {"-1": "删除", "0": "无效", "1": "有效"},
                    "edit": {"type": "select", "required": true, "default": 1}
                },
                {
                    "title": "代交车",
                    "data": "foreign_service",
                    "sName": "foreign_service",
                    "bSortable": false,
                    "render": function(data) {
                        return parseInt(data) === 1 ? '是' : '否';
                    },
                    "value": {"0": "否", "1": "是"},
                    "edit": {"type": "radio", "required": true, "default": 0}
                }
            ]
        }
    });

    $(function(){
        mInfo.init();

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
                    $("#province_code").val(value[0]);
                    $("#city_code").val(value[1]);
                    $("#area_code").val(value[2]);

                    // 获取选中的标签
                    var arrName = this.$children[0].currentLabels;
                    if (arrName.length > 0) {
                        if (arrName && arrName[0]) $("#province_name").val(arrName[0]);
                        if (arrName && arrName[1]) $("#city_name").val(arrName[1]);
                        if (arrName && arrName[2]) $("#area_name").val(arrName[2]);

                    }
                }
            }
        });

        $("#get-address").click(function(){
            // 第一步获取省市区地址
            var arrAddress = objVue.$children[0].currentLabels,
                strAddress = $("#store-address").val();
            if (arrAddress.length <= 0) {
                alert("需要先选择地址省市区信息");
                return false;
            }

            // 获取详细地址
            if (!strAddress) {
                alert("请填写详细地址");
                return false;
            }

            // 获取经纬度信息
            $.ajax({
                url: "<?=Url::toRoute(['store/get-address'])?>",
                data: {
                    address: arrAddress.join("") + strAddress
                },
                type: "get",
                dataType: "json"
            }).done(function(json) {
                layer.msg(json.errMsg, {icon: json.errCode === 0 ? 6 : 5});
                if (json.errCode === 0) {
                    // 处理显示数据
                    $("#store-lon").val(json.data[0]);
                    $("#store-lat").val(json.data[1]);
                }
            }).fail(function(){
                layer.msg("服务器繁忙,请稍候再试...", {icon: 5});
            });
        });
    });
</script>
<?php $this->endBlock(); ?>
