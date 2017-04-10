/**
 * Редактирование квот и цен
 */
var Quotas = {
	init: function() {
		if (typeof(ZalArray) == 'undefined' || typeof(QuotasArray) == 'undefined')
			return;

		this.ZalArray = ZalArray;
		this.QuotasArray = QuotasArray;
		this.Zal = $("#elZal");
		this.ZalBox = $("#elZal-box");
		this.pHidden = $('#popup-hidden');
		this.ZalInf = $(".elZal-inf");
		this.infTitle = this.ZalInf.find('.elZal-inf-title');
		this.infRow = this.ZalInf.find('.elZal-inf-set');
		this.infNum = this.ZalInf.find('.elZal-inf-number');
		this.infPrice = this.ZalInf.find('.elZal-inf-money');
		this.popupCb = $('input[name="popup"]');
		this.newBtn = $('input.new');
		this.saveBtn = $('input.save');
		this.showPopup = this.popupCb.prop('checked');
		this.tbody = $('.q-groups tbody');
		this.saveTimerId = 0;
		this.elementId = this.Zal.data('run');
		this.body = $('body');
		this.backLink = $('.theater-info a');
		this.headDiv = $('#options-panel');
		this.marginDiv = $('#options-panel-margin');
		this.currentRow = 0;

		var tr = Quotas.tbody.find('tr:first');
		var ex = false;
		for (var j in this.QuotasArray) {
			if (this.QuotasArray.hasOwnProperty(j)) {
				var row = this.QuotasArray[j];
				var newTr = tr.clone();
				if (!ex)
					newTr.find('.current').prop('checked', true);
				newTr.attr('id', 'r' + j);
				newTr.find('.price').val(row[0]);
				newTr.find('.color').val(row[1]);
				//newTr.find('.current').val(row[2]);
				var l = row[2].length;
				for (var i1 = 0; i1 < l; i1++) {
					this.ZalArray[row[2][i1]][6] = j;
				}
				Quotas.tbody.append(newTr);
				ex = true;
			}
		}
		if (ex)
			tr.remove();
		for (var i in this.ZalArray) {
			this.lastId = i;
			if (this.ZalArray.hasOwnProperty(i)) {
				var zalItem = this.ZalArray[i];
				this.addItem(zalItem, i);
			}
		}

		this.correctMargin();

		this.ZalBox.on('mouseenter', '.elZal-item', this.hover);
		this.ZalBox.on('mousedown', '.elZal-point', this.mouseDown);
		this.newBtn.on('click', this.newBtnClick);
		this.body.on('click', '.del', this.delBtnClick);
		this.body.on('input', '.price', this.priceChange);
		this.body.on('input', '.color', this.colorChange);
		this.body.on('change', '.current', this.currentChange);
		this.saveBtn.on('click', this.saveAjax);
		this.popupCb.on('click', this.popupCbClick);
		this.backLink.on('click', this.backClick);
	},
	correctMargin: function() {
		Quotas.marginDiv.height(this.headDiv.height() + 10);
		var cnt = Quotas.tbody.children('tr').length;
		if (cnt > 1)
			Quotas.tbody.removeClass('no-delete');
		else
			Quotas.tbody.addClass('no-delete');
		var checked = Quotas.tbody.find('.current:checked');
		if (checked.length) {
			var tr = checked.closest('tr');
			Quotas.currentRow = tr.attr('id').substr(1);
		}
		else
			Quotas.currentRow = 0;
	},
	hover: function() {
		if (Quotas.showPopup) {
			var item = $(this);
			var id = item.attr('id');
			var zalItem = Quotas.ZalArray[id];
			var price = 0;
			if (zalItem[6]) {
				var row = Quotas.QuotasArray[zalItem[6]];
				price = row[0];
			}

			Quotas.infTitle.text(zalItem[3]);
			Quotas.infRow.text(zalItem[4]);
			Quotas.infNum.text(zalItem[5]);
			Quotas.infPrice.text(price);
			Quotas.ZalInf.appendTo($(this));
		}
	},
	addItem: function(zalItem, i) {
		var tmp = $('<div class="elZal-item"><div class="elZal-point">' + zalItem[5] + '</div></div>');
		var point = tmp.children();

		tmp.attr("id", i);
		tmp.css({
			left: "" + zalItem[0] + "px",
			top: "" + zalItem[1] + "px"
		});
		if (zalItem[6]) {
			var row = Quotas.QuotasArray[zalItem[6]];
			point.css({'background-color': row[1]});
		}
		if (zalItem[2]) {
			var val = 'rotate(' + zalItem[2] + 'deg)';
			point.css({
				'-moz-transform': val,
				'-ms-transform': val,
				'-webkit-transform': val,
				'-o-transform': val,
				'transform': val
			});
		}

		Quotas.ZalBox.append(tmp);

		return tmp;
	},
	save: function() {
		if (Quotas.saveTimerId)
			clearTimeout(Quotas.saveTimerId);
		Quotas.saveTimerId = setTimeout(Quotas.saveAjax, 60000);
		Quotas.saveBtn.removeClass('disabled');
	},
	saveAjax: function() {
		if (Quotas.saveBtn.hasClass('disabled'))
			return;
		if (Quotas.saveTimerId)
			clearTimeout(Quotas.saveTimerId);

		Quotas.addSits();

		var data = JSON.stringify(Quotas.QuotasArray);
		$.ajax({
			type: 'POST',
			url: '/ajax/save_quotas.php?ID=' + Quotas.elementId,
			data: data,
			dataType: 'json',
			contentType: 'application/json; charset=utf-8',
			success: function () {
				Quotas.saveBtn.addClass('disabled');
			}
		});
	},
	mouseDown: function() {
		if (!Quotas.currentRow)
			return false;

		var point = $(this);
		var item = point.parent();
		var id = item.attr('id');
		var zalItem = Quotas.ZalArray[id];
		if (zalItem[6] != Quotas.currentRow) {
			zalItem[6] = Quotas.currentRow;
			var row = Quotas.QuotasArray[Quotas.currentRow];
			point.css({'background-color': row[1]});
			Quotas.save();
		}
		else {
            zalItem[6] = null;
            point.css({'background-color': 'transparent'});
            Quotas.save();
        }


	},
	priceChange: function() {
		var input = $(this);
		var rowIndex = input.closest('tr').attr('id').substr(1);
		if (!Quotas.QuotasArray.hasOwnProperty(rowIndex))
			Quotas.QuotasArray[rowIndex] = [];
		Quotas.QuotasArray[rowIndex][0] = parseInt(input.val());
		Quotas.save();
	},
	colorChange: function() {
		var input = $(this);
		var rowIndex = input.closest('tr').attr('id').substr(1);
		if (!Quotas.QuotasArray.hasOwnProperty(rowIndex))
			Quotas.QuotasArray[rowIndex] = [];
		Quotas.QuotasArray[rowIndex][1] = input.val();
		Quotas.save();
	},
	currentChange: function() {
		var input = $(this);
		Quotas.currentRow = input.closest('tr').attr('id').substr(1);
	},
	newBtnClick: function() {
		var tr = Quotas.tbody.find('tr:last');
		var newTr = tr.clone();
		var id = parseInt(newTr.attr('id').substr(1));
		id++;
		newTr.attr('id', 'r' + id);
		newTr.find('.current').prop('checked', true);
		newTr.find('.price').val('');
		newTr.find('.color').val('');
		newTr.find('.current').val('');
		Quotas.tbody.append(newTr);
		Quotas.correctMargin();
	},
	delBtnClick: function() {
		if (Quotas.tbody.hasClass('no-delete'))
			return false;
		var tr = $(this).closest('tr');
		var id = tr.attr('id').substr(1);
		delete Quotas.QuotasArray[id];
		tr.remove();
		Quotas.correctMargin();

		for(var i in Quotas.ZalArray){
			var item = Quotas.ZalArray[i];
            if(item[6] == id){
                item[6] = null;
                $('.elZal-item#'+i+' div').css({'background-color': 'transparent'});
			}
		}

		Quotas.save();
	},
	clearCurrent: function() {
		Quotas.optTitle.val('');
		Quotas.optRow.val('');
		Quotas.optNum.val('');
		Quotas.optX.val('');
		Quotas.optY.val('');
		Quotas.optRotate.val('');
	},
	popupCbClick: function() {
		Quotas.showPopup = Quotas.popupCb.prop('checked');
		if (!Quotas.showPopup)
			Quotas.ZalInf.appendTo(Quotas.pHidden);
	},
	backClick: function() {
		Quotas.saveAjax();
		return true;
	},
	addSits: function() {
		for (var j in Quotas.QuotasArray) {
			if (Quotas.QuotasArray.hasOwnProperty(j)) {
				Quotas.QuotasArray[j][2] = [];
			}
		}
		for (var i in Quotas.ZalArray) {
			if (Quotas.ZalArray.hasOwnProperty(i)) {
				var zalItem = this.ZalArray[i];
				if (zalItem[6]) {
					var row = Quotas.QuotasArray[zalItem[6]];
					row[2].push(parseInt(i));
				}
			}
		}
	}
};

$(document).ready(function() {
	Quotas.init();
});