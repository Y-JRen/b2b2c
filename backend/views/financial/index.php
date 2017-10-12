<?php
    use yii\widgets\LinkPager;

    $this->title = '金融方案管理';
?>
 <script src="../adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<?php
   $this->registerJsFile('../adminlte/plugins/daterangepicker/moment.min.js');
?>

<section class="content-body">
 
	<div class="row">
		<div class="col-sm-12 t-r">
			<div class="pull-left mr-15">
			&nbsp;  <input class="btn btn-primary btn-sm pull-left mr-15" onclick="financial.checkHandle('-1')"  value="新增金融方案"    type="button">
			</div> 
		</div>
    </div>
	<br/>
    <div class="box box-none-border">
        <div class="box-body no-padding">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-list-check">
                    <thead>
                    <tr>
                        <th  >金融方案编号</th>
                        <th  >方案类型</th>
						<th>金融方案名称</th>
                        <th>使用中商品</th>
						<th>发布时间</th>
						<th>修改时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
						<?php if (!empty($list)) {
								foreach ($list as $k => $v) { ?>
								<tr> 
                                    <td><?php echo empty($v['no']) ? '--' : $v['no']; ?></td>
									 <td><?php echo empty($v['type']) ? '--' : $finType[$v['type']]; ?></td>
									<td><?php echo empty($v['name']) ? '--' : $v['name']; ?></td>
									<td><?php echo 0; ?></td>
									<td><?php echo empty($v['create_time']) ? '--' : $v['create_time']; ?></td>
									<td><?php echo strtotime($v['update_time'])>0 ? $v['update_time'] : '--'; ?></td>
									<td>  
									<a href="#" onclick="financial.checkHandle(<?php echo empty($v['id']) ? '0' : $v['id'];?>)"> 编辑</a>
									</td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer pdt-md pull-right bd-t0">
                <?php
                // 显示分页
                echo LinkPager::widget([
                    'pagination' => $pagination,
                    'firstPageLabel' => "首页",
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'lastPageLabel' => '末页',
                ]);
                ?>
            </div>
        </div>
    </div>
</section>

<!--新建编辑弹出层 start-->
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;overflow:scroll;">
    <div class="modal-dialog" role="document" style="width: 1000px;">
        <div class="modal-content">
            <div class="modal-body" id ="modal-body">
                <!-- form 表单 编辑或者新建金融方案-->
                <form id="finForm" method="post" action="/financial/editcreate" >
                    <div class="modal-header">
                        <button type="button" onclick="financial.cancelLayer()" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="reset_but" >×</span></button>
                        <h4 class="modal-title" id="myModalLabelTitle"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel boxshadow-none mb-0 clearfix">
                            <div class="row" style="display:none" id='finno_row'>
                                <div class="form-group col-md-6 col-sm-6">
                                    <label class="control-label col-sm-4 t-r"><span class="c-red pdr-5"></span>金融方案编号：</label>
                                    <div class="col-md-8">
                                        <span id='finNo'> </span>
                                        
                                    </div>
                                </div>
                            </div> 
							<div class="row" >
                                <div class="form-group col-md-6 col-sm-6">
                                    <label class="control-label col-sm-4 t-r"><span class="c-red pdr-5"></span>方案类型：</label>
                                    <div class="col-md-8" >
										<select name='fintype'>
											<?php foreach($finType as $key=>$val){ ?>
												<option value="<?php echo $key;?>" > <?php echo $val;?></option>
											<?php }?>
										</select>
                                        <input type="hidden" id="finid" name="finid" value="">
                                    </div>
                                </div>
                            </div> 
							
							<div class="row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label class="control-label col-sm-4 t-r"><span class="c-red pdr-5"></span>金融方案名称：</label>
                                    <div class="col-md-8"> 
                                        <input type="text" id="finname" name="finname" value="">
                                    </div>
                                </div>
                            </div> 
							<div class="row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label class="control-label col-sm-4 t-r"><span class="c-red pdr-5">*</span>首付：</label>
                                    <div class="col-md-8">
									<input class="qishu" type="checkbox" value="-1" name='periods_default' id='periods_default' checked style='display:none'>
                                        <p class="shou">
											
										<?php foreach($proportionList as $kp=>$vp){ ?>
											<input class="ratio_select" type="checkbox" value="<?php echo $vp['id'];?>" id='periods_<?php echo $vp['id'];?>' name='periods_<?php echo $vp['id'];?>'>
											<?php echo $vp['name'];?>
                                            <input type="hidden" name="periods" value="<?php echo $vp['id']; ?>" >
										<?php }?>
                                        </p>
                                    </div>
                                </div>
                            </div> 
							<div class="row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label class="control-label col-sm-4 t-r"><span class="c-red pdr-5">*</span>周期：</label>
                                    <div class="col-md-8">
                                        <p class="year">
										<input class="qishu" type="checkbox" value="-1" name='proportion_default' id='proportion_default' checked style='display:none'>
										<?php foreach($periodsList as $kpp=>$vpp){ ?>
											<input class="qishu" type="checkbox" value="<?php echo $vpp['id'];?>" name='proportion_<?php echo $vpp['id'];?>' id='proportion_<?php echo $vpp['id'];?>'>
											<?php echo $vpp['name'];?>
                                            <input type="hidden" name="proportion" value="<?php echo $vpp['id']; ?>" >
										<?php }?>
                                        </p>
                                    </div>
                                </div>
                            </div>
							<div class="row" id="table" style="display:none">
                            </div>
							<div class="row">
                                <div class="form-group col-md-6 col-sm-6">
                                    <label class="control-label col-sm-4 t-r"><span class="c-red pdr-5"></span>金融方案描述：</label>
                                    <div class="col-md-8"> 
                                        <input type="text" id="findes" name='findes' value="">
										 <input type="hidden" id="rate" >
                                    </div>
                                </div>
                            </div> 
							
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="financial.cancelLayer()"    class="btn btn-default btn-sm reset_but">取消</button>
                        <button type="button" onclick="financial.submitEditCreateForm()"   id="submit" class="btn btn-sm btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    table{ border: 1px solid #eee; margin: 1px; border-collapse: collapse; }
    table td,table th{border: 1px solid #eee;}
    textarea{ width: 800px; height: 300px; padding: 20px; outline:none}
</style>
<!--新建编辑弹出层 end-->
 <script>


     var financial = {
         //显示编辑添加层
         "checkHandle" : function(id){
             $('#myModal').show();
             var sendData = {};
             sendData.id     = id;
             $.post('/financial/create', sendData, function(res){ 
                 var info = JSON.parse( res );
                 $('#finname').val(info.name);
                 $('#myModalLabelTitle').html(info.title);
                 $('#finid').val(info.id);
                 $('#finNo').html(info.no);

                 if(info.id>0){
                     $('#finno_row').show();
                 }
                 $('#findes').val(info.des);
                 var i = 0;
				 $("[name='checkbox']").removeAttr("checked");
                 for(var key in info.ProgramInfo){
                     i++;
                     $('#proportion_'+info.ProgramInfo[key]['period_id']).attr("checked", true);
                     $('#periods_'+info.ProgramInfo[key]['ratio_id']).attr("checked", true);

                 } 
				 if(i>0){
					  $('#table').show();
				 }
                 var totalData = {
                     header: info.periodsList.header,
                     data: info.periodsList.data
                 }
				// console.log(totalData);
                 var $shouInputs = $('.shou').find('input[type="checkbox"]');
                 var $years = $('.year').find('input[type="checkbox"]');

                 var header = [];
                 var renderData = [];
                 // 表头被选中的数组
                 var headerAry = [];
                 // 首付被选中的数组
                 var tbodyAry = [];
                 var table = new Table();

                 // 初始化表格
                 init();
                 $years.click(function(event) {
					  $('#table').show();
                     /* Act on the event */
                     header = [];
                     // 添加了哪个表头，首付就选哪个数据添加到数组中
                     headerAry = [];
                     $years.each(function(index) {
                         var checked = $(this).prop('checked');
                         if (checked) {
                             header.push(totalData.header[index])
                             headerAry.push(index);
                         }
                     })
                     renderData = [];
                     $shouInputs.each(function(i, el) {
                         var checked = $(this).prop('checked');
                         if (checked) {
                             var d = [];
                             headerAry.forEach(function(el) {

                                 d.push(totalData.data[i][el]);

                             });
                             renderData.push(d)
                         }
                     });
                     // 是否有首付被选中的，有的话就render table
                     var shouCheckedLen = $('.shou').find('input[type="checkbox"]:checked').length;
                     if (shouCheckedLen) {
                         initTable(header, renderData);
                     }

                 });


                 $shouInputs.click(function(event) {
                     /* Act on the event */
					 $('#table').show();
                     renderData = [];
                     $shouInputs.each(function(i, el) {
                         var checked = $(this).prop('checked');
                         if (checked) {
                             var d = [];
                             headerAry.forEach(function(el) {

                                 d.push(totalData.data[i][el]);

                             });
                             renderData.push(d)
                         }
                     });
                     initTable(header, renderData);

                 });

                 var initTable = function(header, renderData) {
                     // console.log('renderData', renderData);
                     table.init({
                         id:'table',
                         header:header,
                         data:renderData
                     });
                 }

                 $('#table').delegate('input.editor-table-input', 'blur', function(e) {
                     console.log(e);
                 })

                 // 最后 保存数据用的
                 $('#btn').click(function(event) {
                     /* Act on the event */
                     console.log(table.saveForm('table'))
                 });


                 function init() {
                     var initHeader = [];
                     var initTbodyData = [];
                     var initHeaderAry = [];
                     $years.each(function(index) {
                         var checked = $(this).prop('checked');
                         if (checked) {
                             initHeader.push(totalData.header[index])
                             initHeaderAry.push(index);
                         }
                     })

                     $shouInputs.each(function(i, el) {
                         var checked = $(this).prop('checked');
                         if (checked) {
                             var d = [];
                             initHeaderAry.forEach(function(el) {

                                 d.push(totalData.data[i][el]);

                             });
                             initTbodyData.push(d)
                         }
                     });

                     table.init({
                         id:'table',
                         header: initHeader,
                         data: initTbodyData
                     })
                 }

             });

         },
         //取消新建弹层
         "cancelLayer" : function(){
             $('#myModal').hide();
         },
         //提交信息
         "submitEditCreateForm" : function(){
			 alert (1111);
            //var sendData = {};
			//console.log(table.saveForm('table'))
            $('#finForm').submit();
         },
     };

     var Table = (function () {
         function Table(Option) {
             this.Option = Option || {};
             this.DataStore = {};
         }

         Table.prototype.createHeader = function(htmls, data) {
             htmls.push('<tr>');
             for (var i in data) {
                 htmls.push('<th>' + data[i] + '</th>');
             }
             htmls.push('</tr>');
         };

         Table.prototype.createRow = function(htmls, data, index) {
             htmls.push('<tr>');
             for (var i in data) {
                 if(i == 0) {
                     htmls.push('<td>' + data[i]+ '</td>');
                 } else {
                     htmls.push('<td>' +'<input type="text" class="editor-table-input" name="house" value="'+ data[i]+'" data-col="'+ i +'" data-row="'+ index +'">' + '</td>');
                 }

             }
             htmls.push('</tr>');
         };


         Table.prototype.render = function(id, tag) {
             var htmls = [];
             var option = this.Option[id];
             if (option['title'] != null) {
                 htmls.push('<div class="title">' + option['title'] + '</div>');
             }
             htmls.push('<table>');
             this.createHeader(htmls, this.DataStore[id]['header']);
             for (var i in this.DataStore[id]['data']) {
                 this.createRow(htmls, this.DataStore[id]['data'][i], i);
             }
             htmls.push('</table>');
             tag.empty().append(htmls.join(''));
             this.setStyle(id, tag);
         };

         Table.prototype.setStyle = function(id, tag) {
             var option = this.Option[id];
             tag.find('.title').css({
                 'font-weight': 'bold',
                 'text-align': 'center',
                 'color': option['titleColor'],
                 'font-size': option['titleSize']
             });
             tag.find('table').css({
                 'width': '100%'
             });
             tag.find('th').css({
                 'color': option['headerColor'],
                 'background-color': option['headerBgColor'],
                 'font-size': option['headerSize']
             });
             tag.find('tr td').css({
                 'color': option['color'],
                 'font-size': option['size'],
                 'text-align': option['align'],
             });
             tag.find('tr:even td').css({
                 'background-color': option['evenBgColor']
             });
             tag.find('tr:odd td').css({
                 'background-color': option['oddBgColor']
             });
             if (option['rowHeight'] != null) {
                 tag.find('tr').find('th:eq(0)').css('height', option['rowHeight']);
                 tag.find('tr').find('td:eq(0)').css('height', option['rowHeight']);
             }
             if (option['columnWidth'] != null) {
                 var td = tag.find('tr').find('th');
                 $.each(td,
                     function(i) {
                         $(this).css('width', option['columnWidth'][i] + '%');
                     });
             }
         };

         Table.prototype.getVal = function(value, defalutValue) {
             if (typeof value == 'undefined') {
                 return defalutValue;
             } else {
                 return value;
             }
         };

         Table.prototype.init = function(option) {
             var id = option['id'];
             var tag = $('#' + id);
             var header = option['header'];
             var data = option['data'];
             this.DataStore[id] = {
                 header: header,
                 data: data
             };
             this.Option[id] = {
                 title: this.getVal(option['title'], null),
                 titleColor: this.getVal(option['titleColor'], 'black'),
                 titleSize: this.getVal(option['titleSize'], 16),
                 headerColor: this.getVal(option['headerColor'], 'black'),
                 headerBgColor: this.getVal(option['headerBgColor'], '#A2FD9A'),
                 headerSize: this.getVal(option['headerSize'], 16),
                 color: this.getVal(option['color'], 'black'),
                 size: this.getVal(option['size'], 16),
                 align: this.getVal(option['align'], 'center'),
                 evenBgColor: this.getVal(option['evenBgColor'], '#E3F4FD'),
                 oddBgColor: this.getVal(option['oddBgColor'], '#FDF0E6'),
                 rowHeight: this.getVal(option['rowHeight'], 34),
                 columnWidth: this.getVal(option['columnWidth'], null)
             };
             this.render(id, tag);
         };

         Table.prototype.getValue = function(id, row, column) {
             return this.DataStore[id]['data'][row - 1][column - 1];
         };
         Table.prototype.setValue = function(id, row, column, value) {
             this.DataStore[id]['data'][row - 1][column - 1] = value;
         };
         Table.prototype.getValues = function(id) {
             return this.DataStore[id]['data'];
         };
         Table.prototype.addRow = function(id, data) {
             this.DataStore[id]['data'].push(data);
         };
         Table.prototype.deleteRow = function(id, row) {
             this.DataStore[id]['data'].splice(row - 1, 1);
         };
         Table.prototype.getRowCount = function(id) {
             return this.DataStore[id]['data'].length;
         };
         Table.prototype.renderAll = function(id) {
             this.render(id, $('#' + id));
         };

         Table.prototype.saveForm = function(id) {
             return $('#' + id).serializeArray();
         }

         return Table;
     }());
 </script>