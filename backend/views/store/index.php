<?php

use yii\helpers\Html;
use backend\widgets\MeTable;
use yii\helpers\Url;
use common\widgets\vue\asset\VueAsset;

/* @var $this yii\web\View */
VueAsset::register($this);

$this->title = '门店管理';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="store-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('新增门店', '#', ['class' => 'btn btn-success btn-flat', 'onclick' => 'm.create()']) ?>
    </div>
    <div class="box-body">
        <?=MeTable::widget([
            'table' => [
                'class' => 'table table-bordered table-hover',
            ],
            'buttonsTemplate' => '',
        ])?>
    </div>
</div>
<?php $this->beginBlock('javascript'); ?>
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
        }
    });

    var m = meTables({
        title: "门店管理",
        searchType: "top",
        search: {render: false},
        bCheckbox: false,
        "bEvent": false,
        table: {
            "bLengthChange": false,
            "iDisplayLength": 20,
            "order": [],
            "aoColumns": [
                {
                    "title": "ID",
                    "data": "id",
                    "sName": "id",
                    "bSortable": false,
                    "edit": {"type": "hidden"}
                },
                {
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
        m.init();

        // 最后使用vue
        objVue = new Vue({
            el: '#cascade',
            data: function () {
                return {
                    options: [{"value":"110000","label":"北京","children":[{"value":"110100","label":"北京市","children":[{"value":"110101","label":"东城区"},{"value":"110102","label":"西城区"},{"value":"110105","label":"朝阳区"},{"value":"110106","label":"丰台区"},{"value":"110107","label":"石景山区"},{"value":"110108","label":"海淀区"},{"value":"110109","label":"门头沟区"},{"value":"110111","label":"房山区"},{"value":"110112","label":"通州区"},{"value":"110113","label":"顺义区"},{"value":"110114","label":"昌平区"},{"value":"110115","label":"大兴区"},{"value":"110116","label":"怀柔区"},{"value":"110117","label":"平谷区"},{"value":"110118","label":"密云区"},{"value":"110119","label":"延庆区"},{"value":"110120","label":"中关村科技园区"}]}]},{"value":"120000","label":"天津","children":[{"value":"120100","label":"天津市","children":[{"value":"120101","label":"和平区"},{"value":"120102","label":"河东区"},{"value":"120103","label":"河西区"},{"value":"120104","label":"南开区"},{"value":"120105","label":"河北区"},{"value":"120106","label":"红桥区"},{"value":"120110","label":"东丽区"},{"value":"120111","label":"西青区"},{"value":"120112","label":"津南区"},{"value":"120113","label":"北辰区"},{"value":"120114","label":"武清区"},{"value":"120115","label":"宝坻区"},{"value":"120116","label":"滨海新区"},{"value":"120117","label":"宁河区"},{"value":"120118","label":"静海区"},{"value":"120119","label":"蓟州区"},{"value":"120120","label":"滨海高新区"}]}]},{"value":"130000","label":"河北省","children":[{"value":"130100","label":"石家庄市","children":[{"value":"130102","label":"长安区"},{"value":"130104","label":"桥西区"},{"value":"130105","label":"新华区"},{"value":"130107","label":"井陉矿区"},{"value":"130108","label":"裕华区"},{"value":"130109","label":"藁城区"},{"value":"130110","label":"鹿泉区"},{"value":"130111","label":"栾城区"},{"value":"130121","label":"井陉县"},{"value":"130123","label":"正定县"},{"value":"130125","label":"行唐县"},{"value":"130126","label":"灵寿县"},{"value":"130127","label":"高邑县"},{"value":"130128","label":"深泽县"},{"value":"130129","label":"赞皇县"},{"value":"130130","label":"无极县"},{"value":"130131","label":"平山县"},{"value":"130132","label":"元氏县"},{"value":"130133","label":"赵县"},{"value":"130181","label":"辛集市"},{"value":"130183","label":"晋州市"},{"value":"130184","label":"新乐市"},{"value":"130185","label":"高新区"},{"value":"130186","label":"经济技术开发区"}]},{"value":"130200","label":"唐山市"},{"value":"130300","label":"秦皇岛市"},{"value":"130400","label":"邯郸市"},{"value":"130500","label":"邢台市"},{"value":"130600","label":"保定市"},{"value":"130700","label":"张家口市"},{"value":"130800","label":"承德市"},{"value":"130900","label":"沧州市"},{"value":"131000","label":"廊坊市"},{"value":"131100","label":"衡水市"}]},{"value":"140000","label":"山西省","children":[{"value":"140100","label":"太原市","children":[{"value":"140105","label":"小店区"},{"value":"140106","label":"迎泽区"},{"value":"140107","label":"杏花岭区"},{"value":"140108","label":"尖草坪区"},{"value":"140109","label":"万柏林区"},{"value":"140110","label":"晋源区"},{"value":"140121","label":"清徐县"},{"value":"140122","label":"阳曲县"},{"value":"140123","label":"娄烦县"},{"value":"140181","label":"古交市"},{"value":"140182","label":"高新阳曲园区"},{"value":"140183","label":"高新汾东园区"},{"value":"140184","label":"高新姚村园区"}]},{"value":"140200","label":"大同市"},{"value":"140300","label":"阳泉市"},{"value":"140400","label":"长治市"},{"value":"140500","label":"晋城市"},{"value":"140600","label":"朔州市"},{"value":"140700","label":"晋中市"},{"value":"140800","label":"运城市"},{"value":"140900","label":"忻州市"},{"value":"141000","label":"临汾市"},{"value":"141100","label":"吕梁市"}]},{"value":"150000","label":"内蒙古自治区","children":[{"value":"150100","label":"呼和浩特市","children":[{"value":"150102","label":"新城区"},{"value":"150103","label":"回民区"},{"value":"150104","label":"玉泉区"},{"value":"150105","label":"赛罕区"},{"value":"150121","label":"土默特左旗"},{"value":"150122","label":"托克托县"},{"value":"150123","label":"和林格尔县"},{"value":"150124","label":"清水河县"},{"value":"150125","label":"武川县"}]},{"value":"150200","label":"包头市"},{"value":"150300","label":"乌海市"},{"value":"150400","label":"赤峰市"},{"value":"150500","label":"通辽市"},{"value":"150600","label":"鄂尔多斯市"},{"value":"150700","label":"呼伦贝尔市"},{"value":"150800","label":"巴彦淖尔市"},{"value":"150900","label":"乌兰察布市"},{"value":"152200","label":"兴安盟"},{"value":"152500","label":"锡林郭勒盟"},{"value":"152900","label":"阿拉善盟"}]},{"value":"210000","label":"辽宁省","children":[{"value":"210100","label":"沈阳市","children":[{"value":"210102","label":"和平区"},{"value":"210103","label":"沈河区"},{"value":"210104","label":"大东区"},{"value":"210105","label":"皇姑区"},{"value":"210106","label":"铁西区"},{"value":"210111","label":"苏家屯区"},{"value":"210112","label":"浑南新区"},{"value":"210113","label":"沈北新区"},{"value":"210114","label":"于洪区"},{"value":"210115","label":"辽中区"},{"value":"210123","label":"康平县"},{"value":"210124","label":"法库县"},{"value":"210181","label":"新民市"},{"value":"210182","label":"高新区"}]},{"value":"210200","label":"大连市"},{"value":"210300","label":"鞍山市"},{"value":"210400","label":"抚顺市"},{"value":"210500","label":"本溪市"},{"value":"210600","label":"丹东市"},{"value":"210700","label":"锦州市"},{"value":"210800","label":"营口市"},{"value":"210900","label":"阜新市"},{"value":"211000","label":"辽阳市"},{"value":"211100","label":"盘锦市"},{"value":"211200","label":"铁岭市"},{"value":"211300","label":"朝阳市"},{"value":"211400","label":"葫芦岛市"}]},{"value":"220000","label":"吉林省","children":[{"value":"220100","label":"长春市","children":[{"value":"220102","label":"南关区"},{"value":"220103","label":"宽城区"},{"value":"220104","label":"朝阳区"},{"value":"220105","label":"二道区"},{"value":"220106","label":"绿园区"},{"value":"220112","label":"双阳区"},{"value":"220113","label":"九台区"},{"value":"220122","label":"农安县"},{"value":"220182","label":"榆树市"},{"value":"220183","label":"德惠市"},{"value":"220184","label":"长春新区"},{"value":"220185","label":"高新技术产业开发区"},{"value":"220186","label":"经济技术开发区"},{"value":"220187","label":"汽车产业开发区"},{"value":"220188","label":"兴隆综合保税区"}]},{"value":"220200","label":"吉林市"},{"value":"220300","label":"四平市"},{"value":"220400","label":"辽源市"},{"value":"220500","label":"通化市"},{"value":"220600","label":"白山市"},{"value":"220700","label":"松原市"},{"value":"220800","label":"白城市"},{"value":"222400","label":"延边朝鲜族自治州"}]},{"value":"230000","label":"黑龙江省","children":[{"value":"230100","label":"哈尔滨市","children":[{"value":"230102","label":"道里区"},{"value":"230103","label":"南岗区"},{"value":"230104","label":"道外区"},{"value":"230108","label":"平房区"},{"value":"230109","label":"松北区"},{"value":"230110","label":"香坊区"},{"value":"230111","label":"呼兰区"},{"value":"230112","label":"阿城区"},{"value":"230113","label":"双城区"},{"value":"230123","label":"依兰县"},{"value":"230124","label":"方正县"},{"value":"230125","label":"宾县"},{"value":"230126","label":"巴彦县"},{"value":"230127","label":"木兰县"},{"value":"230128","label":"通河县"},{"value":"230129","label":"延寿县"},{"value":"230183","label":"尚志市"},{"value":"230184","label":"五常市"},{"value":"230185","label":"哈尔滨新区"},{"value":"230186","label":"高新区"}]},{"value":"230200","label":"齐齐哈尔市"},{"value":"230300","label":"鸡西市"},{"value":"230400","label":"鹤岗市"},{"value":"230500","label":"双鸭山市"},{"value":"230600","label":"大庆市"},{"value":"230700","label":"伊春市"},{"value":"230800","label":"佳木斯市"},{"value":"230900","label":"七台河市"},{"value":"231000","label":"牡丹江市"},{"value":"231100","label":"黑河市"},{"value":"231200","label":"绥化市"},{"value":"232700","label":"大兴安岭地区"}]},{"value":"310000","label":"上海","children":[{"value":"310100","label":"上海市","children":[{"value":"310101","label":"黄浦区"},{"value":"310104","label":"徐汇区"},{"value":"310105","label":"长宁区"},{"value":"310106","label":"静安区"},{"value":"310107","label":"普陀区"},{"value":"310109","label":"虹口区"},{"value":"310110","label":"杨浦区"},{"value":"310112","label":"闵行区"},{"value":"310113","label":"宝山区"},{"value":"310114","label":"嘉定区"},{"value":"310115","label":"浦东新区"},{"value":"310116","label":"金山区"},{"value":"310117","label":"松江区"},{"value":"310118","label":"青浦区"},{"value":"310120","label":"奉贤区"},{"value":"310151","label":"崇明区"},{"value":"310231","label":"张江高新区"},{"value":"310232","label":"紫竹高新区"},{"value":"310233","label":"漕河泾开发区"}]}]},{"value":"320000","label":"江苏省","children":[{"value":"320100","label":"南京市","children":[{"value":"320102","label":"玄武区"},{"value":"320104","label":"秦淮区"},{"value":"320105","label":"建邺区"},{"value":"320106","label":"鼓楼区"},{"value":"320111","label":"浦口区"},{"value":"320113","label":"栖霞区"},{"value":"320114","label":"雨花台区"},{"value":"320115","label":"江宁区"},{"value":"320116","label":"六合区"},{"value":"320117","label":"溧水区"},{"value":"320118","label":"高淳区"},{"value":"320119","label":"江北新区"}]},{"value":"320200","label":"无锡市"},{"value":"320300","label":"徐州市"},{"value":"320400","label":"常州市"},{"value":"320500","label":"苏州市"},{"value":"320600","label":"南通市"},{"value":"320700","label":"连云港市"},{"value":"320800","label":"淮安市"},{"value":"320900","label":"盐城市"},{"value":"321000","label":"扬州市"},{"value":"321100","label":"镇江市"},{"value":"321200","label":"泰州市"},{"value":"321300","label":"宿迁市"}]},{"value":"330000","label":"浙江省","children":[{"value":"330100","label":"杭州市","children":[{"value":"330102","label":"上城区"},{"value":"330103","label":"下城区"},{"value":"330104","label":"江干区"},{"value":"330105","label":"拱墅区"},{"value":"330106","label":"西湖区"},{"value":"330108","label":"滨江区"},{"value":"330109","label":"萧山区"},{"value":"330110","label":"余杭区"},{"value":"330111","label":"富阳区"},{"value":"330122","label":"桐庐县"},{"value":"330127","label":"淳安县"},{"value":"330182","label":"建德市"},{"value":"330185","label":"临安市"},{"value":"330186","label":"高新区"}]},{"value":"330200","label":"宁波市"},{"value":"330300","label":"温州市"},{"value":"330400","label":"嘉兴市"},{"value":"330500","label":"湖州市"},{"value":"330600","label":"绍兴市"},{"value":"330700","label":"金华市"},{"value":"330800","label":"衢州市"},{"value":"330900","label":"舟山市"},{"value":"331000","label":"台州市"},{"value":"331100","label":"丽水市"},{"value":"331200","label":"舟山群岛新区"}]},{"value":"340000","label":"安徽省","children":[{"value":"340100","label":"合肥市","children":[{"value":"340102","label":"瑶海区"},{"value":"340103","label":"庐阳区"},{"value":"340104","label":"蜀山区"},{"value":"340111","label":"包河区"},{"value":"340121","label":"长丰县"},{"value":"340122","label":"肥东县"},{"value":"340123","label":"肥西县"},{"value":"340124","label":"庐江县"},{"value":"340181","label":"巢湖市"},{"value":"340184","label":"经济技术开发区"},{"value":"340185","label":"高新技术开发区"},{"value":"340186","label":"北城新区"},{"value":"340187","label":"滨湖新区"},{"value":"340188","label":"政务文化新区"},{"value":"340189","label":"新站综合开发试验区"}]},{"value":"340200","label":"芜湖市"},{"value":"340300","label":"蚌埠市"},{"value":"340400","label":"淮南市"},{"value":"340500","label":"马鞍山市"},{"value":"340600","label":"淮北市"},{"value":"340700","label":"铜陵市"},{"value":"340800","label":"安庆市"},{"value":"341000","label":"黄山市"},{"value":"341100","label":"滁州市"},{"value":"341200","label":"阜阳市"},{"value":"341300","label":"宿州市"},{"value":"341500","label":"六安市"},{"value":"341600","label":"亳州市"},{"value":"341700","label":"池州市"},{"value":"341800","label":"宣城市"}]},{"value":"350000","label":"福建省","children":[{"value":"350100","label":"福州市","children":[{"value":"350102","label":"鼓楼区"},{"value":"350103","label":"台江区"},{"value":"350104","label":"仓山区"},{"value":"350105","label":"马尾区"},{"value":"350111","label":"晋安区"},{"value":"350121","label":"闽侯县"},{"value":"350122","label":"连江县"},{"value":"350123","label":"罗源县"},{"value":"350124","label":"闽清县"},{"value":"350125","label":"永泰县"},{"value":"350128","label":"平潭县"},{"value":"350181","label":"福清市"},{"value":"350182","label":"长乐市"},{"value":"350183","label":"福州新区"}]},{"value":"350200","label":"厦门市"},{"value":"350300","label":"莆田市"},{"value":"350400","label":"三明市"},{"value":"350500","label":"泉州市"},{"value":"350600","label":"漳州市"},{"value":"350700","label":"南平市"},{"value":"350800","label":"龙岩市"},{"value":"350900","label":"宁德市"}]},{"value":"360000","label":"江西省","children":[{"value":"360100","label":"南昌市","children":[{"value":"360102","label":"东湖区"},{"value":"360103","label":"西湖区"},{"value":"360104","label":"青云谱区"},{"value":"360105","label":"湾里区"},{"value":"360111","label":"青山湖区"},{"value":"360112","label":"新建区"},{"value":"360121","label":"南昌县"},{"value":"360123","label":"安义县"},{"value":"360124","label":"进贤县"},{"value":"360125","label":"红谷滩新区"},{"value":"360126","label":"高新区"},{"value":"360127","label":"经济开发区"},{"value":"360128","label":"小蓝开发区"},{"value":"360129","label":"桑海开发区"},{"value":"360130","label":"望城新区"},{"value":"360131","label":"赣江新区"}]},{"value":"360200","label":"景德镇市"},{"value":"360300","label":"萍乡市"},{"value":"360400","label":"九江市"},{"value":"360500","label":"新余市"},{"value":"360600","label":"鹰潭市"},{"value":"360700","label":"赣州市"},{"value":"360800","label":"吉安市"},{"value":"360900","label":"宜春市"},{"value":"361000","label":"抚州市"},{"value":"361100","label":"上饶市"}]},{"value":"370000","label":"山东省","children":[{"value":"370100","label":"济南市","children":[{"value":"370102","label":"历下区"},{"value":"370103","label":"市中区"},{"value":"370104","label":"槐荫区"},{"value":"370105","label":"天桥区"},{"value":"370112","label":"历城区"},{"value":"370113","label":"长清区"},{"value":"370114","label":"章丘区"},{"value":"370124","label":"平阴县"},{"value":"370125","label":"济阳县"},{"value":"370126","label":"商河县"},{"value":"370182","label":"高新区"}]},{"value":"370200","label":"青岛市"},{"value":"370300","label":"淄博市"},{"value":"370400","label":"枣庄市"},{"value":"370500","label":"东营市"},{"value":"370600","label":"烟台市"},{"value":"370700","label":"潍坊市"},{"value":"370800","label":"济宁市"},{"value":"370900","label":"泰安市"},{"value":"371000","label":"威海市"},{"value":"371100","label":"日照市"},{"value":"371200","label":"莱芜市"},{"value":"371300","label":"临沂市"},{"value":"371400","label":"德州市"},{"value":"371500","label":"聊城市"},{"value":"371600","label":"滨州市"},{"value":"371700","label":"菏泽市"}]},{"value":"410000","label":"河南省","children":[{"value":"410100","label":"郑州市","children":[{"value":"410102","label":"中原区"},{"value":"410103","label":"二七区"},{"value":"410104","label":"管城回族区"},{"value":"410105","label":"金水区"},{"value":"410106","label":"上街区"},{"value":"410108","label":"惠济区"},{"value":"410122","label":"中牟县"},{"value":"410181","label":"巩义市"},{"value":"410182","label":"荥阳市"},{"value":"410183","label":"新密市"},{"value":"410184","label":"新郑市"},{"value":"410185","label":"登封市"},{"value":"410186","label":"郑东新区"},{"value":"410187","label":"郑汴新区"},{"value":"410188","label":"高新开发区"},{"value":"410189","label":"经济开发区"}]},{"value":"410200","label":"开封市"},{"value":"410300","label":"洛阳市"},{"value":"410400","label":"平顶山市"},{"value":"410500","label":"安阳市"},{"value":"410600","label":"鹤壁市"},{"value":"410700","label":"新乡市"},{"value":"410800","label":"焦作市"},{"value":"410900","label":"濮阳市"},{"value":"411000","label":"许昌市"},{"value":"411100","label":"漯河市"},{"value":"411200","label":"三门峡市"},{"value":"411300","label":"南阳市"},{"value":"411400","label":"商丘市"},{"value":"411500","label":"信阳市"},{"value":"411600","label":"周口市"},{"value":"411700","label":"驻马店市"},{"value":"419001","label":"济源市"}]},{"value":"420000","label":"湖北省","children":[{"value":"420100","label":"武汉市","children":[{"value":"420102","label":"江岸区"},{"value":"420103","label":"江汉区"},{"value":"420104","label":"硚口区"},{"value":"420105","label":"汉阳区"},{"value":"420106","label":"武昌区"},{"value":"420107","label":"青山区"},{"value":"420111","label":"洪山区"},{"value":"420112","label":"东西湖区"},{"value":"420113","label":"汉南区"},{"value":"420114","label":"蔡甸区"},{"value":"420115","label":"江夏区"},{"value":"420116","label":"黄陂区"},{"value":"420117","label":"新洲区"},{"value":"420118","label":"经济技术开发区"}]},{"value":"420200","label":"黄石市"},{"value":"420300","label":"十堰市"},{"value":"420500","label":"宜昌市"},{"value":"420600","label":"襄阳市"},{"value":"420700","label":"鄂州市"},{"value":"420800","label":"荆门市"},{"value":"420900","label":"孝感市"},{"value":"421000","label":"荆州市"},{"value":"421100","label":"黄冈市"},{"value":"421200","label":"咸宁市"},{"value":"421300","label":"随州市"},{"value":"422800","label":"恩施土家族苗族自治州"},{"value":"429004","label":"仙桃市"},{"value":"429005","label":"潜江市"},{"value":"429006","label":"天门市"},{"value":"429021","label":"神农架林区"}]},{"value":"430000","label":"湖南省","children":[{"value":"430100","label":"长沙市","children":[{"value":"430102","label":"芙蓉区"},{"value":"430103","label":"天心区"},{"value":"430104","label":"岳麓区"},{"value":"430105","label":"开福区"},{"value":"430111","label":"雨花区"},{"value":"430112","label":"望城区"},{"value":"430121","label":"长沙县"},{"value":"430124","label":"宁乡县"},{"value":"430181","label":"浏阳市"},{"value":"430182","label":"湘江新区"}]},{"value":"430200","label":"株洲市"},{"value":"430300","label":"湘潭市"},{"value":"430400","label":"衡阳市"},{"value":"430500","label":"邵阳市"},{"value":"430600","label":"岳阳市"},{"value":"430700","label":"常德市"},{"value":"430800","label":"张家界市"},{"value":"430900","label":"益阳市"},{"value":"431000","label":"郴州市"},{"value":"431100","label":"永州市"},{"value":"431200","label":"怀化市"},{"value":"431300","label":"娄底市"},{"value":"433100","label":"湘西土家族苗族自治州"}]},{"value":"440000","label":"广东省","children":[{"value":"440100","label":"广州市","children":[{"value":"440103","label":"荔湾区"},{"value":"440104","label":"越秀区"},{"value":"440105","label":"海珠区"},{"value":"440106","label":"天河区"},{"value":"440111","label":"白云区"},{"value":"440112","label":"黄埔区"},{"value":"440113","label":"番禺区"},{"value":"440114","label":"花都区"},{"value":"440115","label":"南沙新区"},{"value":"440117","label":"从化区"},{"value":"440118","label":"增城区"}]},{"value":"440200","label":"韶关市"},{"value":"440300","label":"深圳市"},{"value":"440400","label":"珠海市"},{"value":"440500","label":"汕头市"},{"value":"440600","label":"佛山市"},{"value":"440700","label":"江门市"},{"value":"440800","label":"湛江市"},{"value":"440900","label":"茂名市"},{"value":"441200","label":"肇庆市"},{"value":"441300","label":"惠州市"},{"value":"441400","label":"梅州市"},{"value":"441500","label":"汕尾市"},{"value":"441600","label":"河源市"},{"value":"441700","label":"阳江市"},{"value":"441800","label":"清远市"},{"value":"441900","label":"东莞市"},{"value":"442000","label":"中山市"},{"value":"445100","label":"潮州市"},{"value":"445200","label":"揭阳市"},{"value":"445300","label":"云浮市"}]},{"value":"450000","label":"广西壮族自治区","children":[{"value":"450100","label":"南宁市","children":[{"value":"450102","label":"兴宁区"},{"value":"450103","label":"青秀区"},{"value":"450105","label":"江南区"},{"value":"450107","label":"西乡塘区"},{"value":"450108","label":"良庆区"},{"value":"450109","label":"邕宁区"},{"value":"450110","label":"武鸣区"},{"value":"450123","label":"隆安县"},{"value":"450124","label":"马山县"},{"value":"450125","label":"上林县"},{"value":"450126","label":"宾阳县"},{"value":"450127","label":"横县"},{"value":"450128","label":"埌东新区"}]},{"value":"450200","label":"柳州市"},{"value":"450300","label":"桂林市"},{"value":"450400","label":"梧州市"},{"value":"450500","label":"北海市"},{"value":"450600","label":"防城港市"},{"value":"450700","label":"钦州市"},{"value":"450800","label":"贵港市"},{"value":"450900","label":"玉林市"},{"value":"451000","label":"百色市"},{"value":"451100","label":"贺州市"},{"value":"451200","label":"河池市"},{"value":"451300","label":"来宾市"},{"value":"451400","label":"崇左市"}]},{"value":"460000","label":"海南省","children":[{"value":"460100","label":"海口市","children":[{"value":"460105","label":"秀英区"},{"value":"460106","label":"龙华区"},{"value":"460107","label":"琼山区"},{"value":"460108","label":"美兰区"}]},{"value":"460200","label":"三亚市"},{"value":"460300","label":"三沙市"},{"value":"460400","label":"儋州市"},{"value":"469001","label":"五指山市"},{"value":"469002","label":"琼海市"},{"value":"469005","label":"文昌市"},{"value":"469006","label":"万宁市"},{"value":"469007","label":"东方市"},{"value":"469021","label":"定安县"},{"value":"469022","label":"屯昌县"},{"value":"469023","label":"澄迈县"},{"value":"469024","label":"临高县"},{"value":"469025","label":"白沙黎族自治县"},{"value":"469026","label":"昌江黎族自治县"},{"value":"469027","label":"乐东黎族自治县"},{"value":"469028","label":"陵水黎族自治县"},{"value":"469029","label":"保亭黎族苗族自治县"},{"value":"469030","label":"琼中黎族苗族自治县"}]},{"value":"500000","label":"重庆","children":[{"value":"500100","label":"重庆市","children":[{"value":"500101","label":"万州区"},{"value":"500102","label":"涪陵区"},{"value":"500103","label":"渝中区"},{"value":"500104","label":"大渡口区"},{"value":"500105","label":"江北区"},{"value":"500106","label":"沙坪坝区"},{"value":"500107","label":"九龙坡区"},{"value":"500108","label":"南岸区"},{"value":"500109","label":"北碚区"},{"value":"500110","label":"綦江区"},{"value":"500111","label":"大足区"},{"value":"500112","label":"渝北区"},{"value":"500113","label":"巴南区"},{"value":"500114","label":"黔江区"},{"value":"500115","label":"长寿区"},{"value":"500116","label":"江津区"},{"value":"500117","label":"合川区"},{"value":"500118","label":"永川区"},{"value":"500119","label":"南川区"},{"value":"500120","label":"璧山区"},{"value":"500151","label":"铜梁区"},{"value":"500152","label":"潼南区"},{"value":"500153","label":"荣昌区"},{"value":"500154","label":"开州区"},{"value":"500155","label":"梁平区"},{"value":"500156","label":"武隆区"},{"value":"500229","label":"城口县"},{"value":"500230","label":"丰都县"},{"value":"500231","label":"垫江县"},{"value":"500233","label":"忠县"},{"value":"500235","label":"云阳县"},{"value":"500236","label":"奉节县"},{"value":"500237","label":"巫山县"},{"value":"500238","label":"巫溪县"},{"value":"500240","label":"石柱土家族自治县"},{"value":"500241","label":"秀山土家族苗族自治县"},{"value":"500242","label":"酉阳土家族苗族自治县"},{"value":"500243","label":"彭水苗族土家族自治县"},{"value":"500300","label":"两江新区"},{"value":"500301","label":"高新区"},{"value":"500302","label":"璧山高新区"}]}]},{"value":"510000","label":"四川省","children":[{"value":"510100","label":"成都市","children":[{"value":"510104","label":"锦江区"},{"value":"510105","label":"青羊区"},{"value":"510106","label":"金牛区"},{"value":"510107","label":"武侯区"},{"value":"510108","label":"成华区"},{"value":"510112","label":"龙泉驿区"},{"value":"510113","label":"青白江区"},{"value":"510114","label":"新都区"},{"value":"510115","label":"温江区"},{"value":"510116","label":"双流区"},{"value":"510117","label":"郫都区"},{"value":"510121","label":"金堂县"},{"value":"510129","label":"大邑县"},{"value":"510131","label":"蒲江县"},{"value":"510132","label":"新津县"},{"value":"510181","label":"都江堰市"},{"value":"510182","label":"彭州市"},{"value":"510183","label":"邛崃市"},{"value":"510184","label":"崇州市"},{"value":"510185","label":"简阳市"},{"value":"510186","label":"天府新区"},{"value":"510187","label":"高新南区"},{"value":"510188","label":"高新西区"}]},{"value":"510300","label":"自贡市"},{"value":"510400","label":"攀枝花市"},{"value":"510500","label":"泸州市"},{"value":"510600","label":"德阳市"},{"value":"510700","label":"绵阳市"},{"value":"510800","label":"广元市"},{"value":"510900","label":"遂宁市"},{"value":"511000","label":"内江市"},{"value":"511100","label":"乐山市"},{"value":"511300","label":"南充市"},{"value":"511400","label":"眉山市"},{"value":"511500","label":"宜宾市"},{"value":"511600","label":"广安市"},{"value":"511700","label":"达州市"},{"value":"511800","label":"雅安市"},{"value":"511900","label":"巴中市"},{"value":"512000","label":"资阳市"},{"value":"513200","label":"阿坝藏族羌族自治州"},{"value":"513300","label":"甘孜藏族自治州"},{"value":"513400","label":"凉山彝族自治州"}]},{"value":"520000","label":"贵州省","children":[{"value":"520100","label":"贵阳市","children":[{"value":"520102","label":"南明区"},{"value":"520103","label":"云岩区"},{"value":"520111","label":"花溪区"},{"value":"520112","label":"乌当区"},{"value":"520113","label":"白云区"},{"value":"520115","label":"观山湖区"},{"value":"520121","label":"开阳县"},{"value":"520122","label":"息烽县"},{"value":"520123","label":"修文县"},{"value":"520181","label":"清镇市"},{"value":"520182","label":"贵安新区"},{"value":"520183","label":"高新区"}]},{"value":"520200","label":"六盘水市"},{"value":"520300","label":"遵义市"},{"value":"520400","label":"安顺市"},{"value":"520500","label":"毕节市"},{"value":"520600","label":"铜仁市"},{"value":"522300","label":"黔西南布依族苗族自治州"},{"value":"522600","label":"黔东南苗族侗族自治州"},{"value":"522700","label":"黔南布依族苗族自治州"}]},{"value":"530000","label":"云南省","children":[{"value":"530100","label":"昆明市","children":[{"value":"530102","label":"五华区"},{"value":"530103","label":"盘龙区"},{"value":"530111","label":"官渡区"},{"value":"530112","label":"西山区"},{"value":"530113","label":"东川区"},{"value":"530114","label":"呈贡区"},{"value":"530115","label":"晋宁区"},{"value":"530124","label":"富民县"},{"value":"530125","label":"宜良县"},{"value":"530126","label":"石林彝族自治县"},{"value":"530127","label":"嵩明县"},{"value":"530128","label":"禄劝彝族苗族自治县"},{"value":"530129","label":"寻甸回族彝族自治县 "},{"value":"530181","label":"安宁市"},{"value":"530182","label":"滇中新区"},{"value":"530183","label":"高新区"}]},{"value":"530300","label":"曲靖市"},{"value":"530400","label":"玉溪市"},{"value":"530500","label":"保山市"},{"value":"530600","label":"昭通市"},{"value":"530700","label":"丽江市"},{"value":"530800","label":"普洱市"},{"value":"530900","label":"临沧市"},{"value":"532300","label":"楚雄彝族自治州"},{"value":"532500","label":"红河哈尼族彝族自治州"},{"value":"532600","label":"文山壮族苗族自治州"},{"value":"532800","label":"西双版纳傣族自治州"},{"value":"532900","label":"大理白族自治州"},{"value":"533100","label":"德宏傣族景颇族自治州"},{"value":"533300","label":"怒江傈僳族自治州"},{"value":"533400","label":"迪庆藏族自治州"}]},{"value":"540000","label":"西藏自治区","children":[{"value":"540100","label":"拉萨市","children":[{"value":"540102","label":"城关区"},{"value":"540103","label":"堆龙德庆区"},{"value":"540121","label":"林周县"},{"value":"540122","label":"当雄县"},{"value":"540123","label":"尼木县"},{"value":"540124","label":"曲水县"},{"value":"540126","label":"达孜县"},{"value":"540127","label":"墨竹工卡县"}]},{"value":"540200","label":"日喀则市"},{"value":"540300","label":"昌都市"},{"value":"540400","label":"林芝市"},{"value":"540500","label":"山南市"},{"value":"542400","label":"那曲地区"},{"value":"542500","label":"阿里地区"}]},{"value":"610000","label":"陕西省","children":[{"value":"610100","label":"西安市","children":[{"value":"610102","label":"新城区"},{"value":"610103","label":"碑林区"},{"value":"610104","label":"莲湖区"},{"value":"610111","label":"灞桥区"},{"value":"610112","label":"未央区"},{"value":"610113","label":"雁塔区"},{"value":"610114","label":"阎良区"},{"value":"610115","label":"临潼区"},{"value":"610116","label":"长安区"},{"value":"610117","label":"高陵区"},{"value":"610118","label":"鄠邑区"},{"value":"610122","label":"蓝田县"},{"value":"610124","label":"周至县"},{"value":"610127","label":"曲江新区"},{"value":"610128","label":"高新区"}]},{"value":"610200","label":"铜川市"},{"value":"610300","label":"宝鸡市"},{"value":"610400","label":"咸阳市"},{"value":"610500","label":"渭南市"},{"value":"610600","label":"延安市"},{"value":"610700","label":"汉中市"},{"value":"610800","label":"榆林市"},{"value":"610900","label":"安康市"},{"value":"611000","label":"商洛市"},{"value":"611100","label":"西咸新区"}]},{"value":"620000","label":"甘肃省","children":[{"value":"620100","label":"兰州市","children":[{"value":"620102","label":"城关区"},{"value":"620103","label":"七里河区"},{"value":"620104","label":"西固区"},{"value":"620105","label":"安宁区"},{"value":"620111","label":"红古区"},{"value":"620121","label":"永登县"},{"value":"620122","label":"皋兰县"},{"value":"620123","label":"榆中县"},{"value":"620124","label":"兰州新区"},{"value":"620125","label":"高新区"},{"value":"620126","label":"经济开发区"}]},{"value":"620200","label":"嘉峪关市"},{"value":"620300","label":"金昌市"},{"value":"620400","label":"白银市"},{"value":"620500","label":"天水市"},{"value":"620600","label":"武威市"},{"value":"620700","label":"张掖市"},{"value":"620800","label":"平凉市"},{"value":"620900","label":"酒泉市"},{"value":"621000","label":"庆阳市"},{"value":"621100","label":"定西市"},{"value":"621200","label":"陇南市"},{"value":"622900","label":"临夏回族自治州"},{"value":"623000","label":"甘南藏族自治州"}]},{"value":"630000","label":"青海省","children":[{"value":"630100","label":"西宁市","children":[{"value":"630102","label":"城东区"},{"value":"630103","label":"城中区"},{"value":"630104","label":"城西区"},{"value":"630105","label":"城北区"},{"value":"630121","label":"大通回族土族自治县"},{"value":"630122","label":"湟中县"},{"value":"630123","label":"湟源县"}]},{"value":"630200","label":"海东市"},{"value":"632200","label":"海北藏族自治州"},{"value":"632300","label":"黄南藏族自治州"},{"value":"632500","label":"海南藏族自治州"},{"value":"632600","label":"果洛藏族自治州"},{"value":"632700","label":"玉树藏族自治州"},{"value":"632800","label":"海西蒙古族藏族自治州"}]},{"value":"640000","label":"宁夏回族自治区","children":[{"value":"640100","label":"银川市","children":[{"value":"640104","label":"兴庆区"},{"value":"640105","label":"西夏区"},{"value":"640106","label":"金凤区"},{"value":"640121","label":"永宁县"},{"value":"640122","label":"贺兰县"},{"value":"640181","label":"灵武市"},{"value":"640182","label":"经济开发区"}]},{"value":"640200","label":"石嘴山市"},{"value":"640300","label":"吴忠市"},{"value":"640400","label":"固原市"},{"value":"640500","label":"中卫市"}]},{"value":"650000","label":"新疆维吾尔自治区","children":[{"value":"650100","label":"乌鲁木齐市","children":[{"value":"650102","label":"天山区"},{"value":"650103","label":"沙依巴克区"},{"value":"650104","label":"新市区"},{"value":"650105","label":"水磨沟区"},{"value":"650106","label":"头屯河区"},{"value":"650107","label":"达坂城区"},{"value":"650109","label":"米东区"},{"value":"650121","label":"乌鲁木齐县"}]},{"value":"650200","label":"克拉玛依市"},{"value":"650400","label":"吐鲁番市"},{"value":"650500","label":"哈密市"},{"value":"652300","label":"昌吉回族自治州"},{"value":"652700","label":"博尔塔拉蒙古自治州"},{"value":"652800","label":"巴音郭楞蒙古自治州"},{"value":"652900","label":"阿克苏地区"},{"value":"653000","label":"克孜勒苏柯尔克孜自治州"},{"value":"653100","label":"喀什地区"},{"value":"653200","label":"和田地区"},{"value":"654000","label":"伊犁哈萨克自治州"},{"value":"654200","label":"塔城地区"},{"value":"654300","label":"阿勒泰地区"},{"value":"659001","label":"石河子市"},{"value":"659002","label":"阿拉尔市"},{"value":"659003","label":"图木舒克市"},{"value":"659004","label":"五家渠市"},{"value":"659005","label":"北屯市"},{"value":"659006","label":"铁门关市"},{"value":"659007","label":"双河市"},{"value":"659008","label":"可克达拉市"},{"value":"659009","label":"昆玉市"}]},{"value":"710000","label":"台湾","children":[{"value":"710100","label":"台北市","children":[{"value":"710101","label":"松山区"},{"value":"710102","label":"信义区"},{"value":"710103","label":"大安区"},{"value":"710104","label":"中山区"},{"value":"710105","label":"中正区"},{"value":"710106","label":"大同区"},{"value":"710107","label":"万华区"},{"value":"710108","label":"文山区"},{"value":"710109","label":"南港区"},{"value":"710110","label":"内湖区"},{"value":"710111","label":"士林区"},{"value":"710112","label":"北投区"}]},{"value":"710200","label":"高雄市"},{"value":"710300","label":"基隆市"},{"value":"710400","label":"台中市"},{"value":"710500","label":"台南市"},{"value":"710600","label":"新竹市"},{"value":"710700","label":"嘉义市"},{"value":"710800","label":"新北市"},{"value":"712200","label":"宜兰县"},{"value":"712300","label":"桃园市"},{"value":"712400","label":"新竹县"},{"value":"712500","label":"苗栗县"},{"value":"712700","label":"彰化县"},{"value":"712800","label":"南投县"},{"value":"712900","label":"云林县"},{"value":"713000","label":"嘉义县"},{"value":"713300","label":"屏东县"},{"value":"713400","label":"台东县"},{"value":"713500","label":"花莲县"},{"value":"713600","label":"澎湖县"},{"value":"713700","label":"金门县"},{"value":"713800","label":"连江县"}]},{"value":"810000","label":"香港特别行政区","children":[{"value":"810100","label":"香港岛","children":[{"value":"810101","label":"中西区"},{"value":"810102","label":"湾仔区"},{"value":"810103","label":"东区"},{"value":"810104","label":"南区"}]},{"value":"810200","label":"九龙"},{"value":"810300","label":"新界"}]},{"value":"820000","label":"澳门特别行政区","children":[{"value":"820100","label":"澳门半岛","children":[{"value":"820101","label":"花地玛堂区"},{"value":"820102","label":"圣安多尼堂区"},{"value":"820103","label":"大堂区"},{"value":"820104","label":"望德堂区"},{"value":"820105","label":"风顺堂区"}]},{"value":"820200","label":"氹仔岛"},{"value":"820300","label":"路环岛"}]},{"value":"900000","label":"钓鱼岛"}],
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
                url: "<?=Url::toRoute(['get-address'])?>",
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
