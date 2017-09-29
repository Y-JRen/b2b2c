 

var financial = {
	  //显示编辑添加层
    "checkHandle" : function(id){
		$('#myModal').show();
        var sendData = {};
        sendData.id     = id;  
		$.post('/financial/create', sendData, function(res){
			console.log(res); 
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
			for(var key in info.ProgramInfo){
				i++;
				$('#proportion_'+info.ProgramInfo[key]['period_id']).attr("checked", true);
				$('#periods_'+info.ProgramInfo[key]['ratio_id']).attr("checked", true);

			}
			if(i>0){

				for(var key in info.ProgramInfo){
					//financial.createTable('row', info.ProgramInfo[key]['period_id']);
					//financial.createTable('column', info.ProgramInfo[key]['ratio_id']);
				}


			}
		}); 
        
    },
    //取消新建弹层
    "cancelLayer" : function(){
        $('#myModal').hide();
    },
	//提交信息
    "submitEditCreateForm" : function(){
        var sendData = {};
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