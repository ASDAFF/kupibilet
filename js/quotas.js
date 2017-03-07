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
		this.popupCb = $('input[name="popup"]');
		this.newBtn = $('input.new');
		this.saveBtn = $('input.save');
		this.showPopup = this.popupCb.prop('checked');
		this.tbody = $('.q-groups tbody');
		/*this.currentId = 0;
		this.dragRegime = 0;
		this.dragItem = false;
		this.dragId = 0;
		this.dX = 0;
		this.dY = 0;
		this.lastId = 0;*/
		this.saveTimerId = 0;
		this.elementId = this.Zal.data('run');
		this.body = $('body');
		this.backLink = $('.theater-info a');
		this.headDiv = $('#options-panel');
		this.marginDiv = $('#options-panel-margin');

		for (var i in this.ZalArray) {
			this.lastId = i;
			if (this.ZalArray.hasOwnProperty(i)) {
				var zalItem = this.ZalArray[i];
				this.addItem(zalItem, i);
			}
		}
		this.correctMargin();

		this.ZalBox.on('mouseenter', '.elZal-item', this.hover);
		/*this.ZalBox.on('mousedown', '.elZal-point', this.mouseDown);
		this.body.on('mouseup', this.mouseUp);
		this.body.on('mousemove', this.mouseMove);
		this.Zal.on('mouseup', this.zalMouseUp);
		this.optTitle.on('input', this.titleInput);
		this.optRow.on('input', this.rowInput);
		this.optNum.on('input', this.numInput);
		this.optX.on('input', this.xInput);
		this.optY.on('input', this.yInput);
		this.optRotate.on('input', this.rotateInput);*/
		this.newBtn.on('click', this.newBtnClick);
		this.body.on('click', '.del', this.delBtnClick);
		/*this.saveBtn.on('click', this.saveAjax);
		this.popupCb.on('click', this.popupCbClick);
		this.backLink.on('click', this.backClick);*/
	},
	correctMargin: function() {
		this.marginDiv.height(this.headDiv.height() + 10);
	},
	hover: function() {
		if (Quotas.showPopup) {
			var item = $(this);
			var id = item.attr('id');
			var zalItem = Quotas.ZalArray[id];
			Quotas.infTitle.text(zalItem[3]);
			Quotas.infRow.text(zalItem[4]);
			Quotas.infNum.text(zalItem[5]);
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
	mouseDown: function(e) {
		var item = $(this).parent();
		var id = item.attr('id');
		var zalItem = Quotas.ZalArray[id];
		Quotas.optTitle.val(zalItem[3]);
		Quotas.optRow.val(zalItem[4]);
		Quotas.optNum.val(zalItem[5]);
		Quotas.optX.val(zalItem[0]);
		Quotas.optY.val(zalItem[1]);
		Quotas.optRotate.val(zalItem[2]);
		Quotas.currentId = id;
		Quotas.dragId = id;
		Quotas.dragItem = item;
		Quotas.delBtn.removeClass('disabled');
		Quotas.ZalBox.find('.current').removeClass('current');
		item.addClass('current');


		if (!e.altKey) {
			Quotas.dragRegime = 1;
			var pos = Quotas.dragItem.position();
			Quotas.dX = e.pageX - pos.left;
			Quotas.dY = e.pageY - pos.top;
		}
		else {
			Quotas.dragRegime = 2;
			Quotas.dX = e.pageX + e.pageY - zalItem[2];
		}
	},
	titleInput: function() {
		if (!Quotas.currentId)
			return false;

		var zalItem = Quotas.ZalArray[Quotas.currentId];
		zalItem[3] = $(this).val();
		Quotas.save();
	},
	rowInput: function() {
		if (!Quotas.currentId)
			return false;

		var zalItem = Quotas.ZalArray[Quotas.currentId];
		zalItem[4] = $(this).val();
		Quotas.save();
	},
	numInput: function() {
		if (!Quotas.currentId)
			return false;

		var zalItem = Quotas.ZalArray[Quotas.currentId];
		zalItem[5] = $(this).val();
		var div = $('.elZal-item#' + Quotas.currentId + ' .elZal-point');
		div.text(zalItem[5]);

		Quotas.save();
	},
	xInput: function() {
		if (!Quotas.currentId)
			return false;

		var zalItem = Quotas.ZalArray[Quotas.currentId];
		zalItem[0] = parseInt($(this).val());
		var div = $('.elZal-item#' + Quotas.currentId);
		div.css({
			left: "" + zalItem[0] + "px"
		});
		Quotas.save();
	},
	yInput: function() {
		if (!Quotas.currentId)
			return false;

		var zalItem = Quotas.ZalArray[Quotas.currentId];
		zalItem[1] = parseInt($(this).val());
		var div = $('.elZal-item#' + Quotas.currentId);
		div.css({
			top: "" + zalItem[1] + "px"
		});
		Quotas.save();
	},
	rotateInput: function() {
		if (!Quotas.currentId)
			return false;

		var zalItem = Quotas.ZalArray[Quotas.currentId];
		zalItem[2] = parseInt($(this).val());
		var div = $('.elZal-item#' + Quotas.currentId);
		var val = 'rotate(' + zalItem[2] + 'deg)';
		div.children('.elZal-point').css({
			'-moz-transform': val,
			'-ms-transform': val,
			'-webkit-transform': val,
			'-o-transform': val,
			'transform': val
		});
		Quotas.save();
	},
	newBtnClick: function() {
		var tr = Quotas.tbody.find('tr:last');
		Quotas.tbody.append(tr.clone());
		Quotas.correctMargin();
	},
	delBtnClick: function() {
		var tr = $(this).closest('tr');
		tr.remove();
		Quotas.correctMargin();
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
	}
};

$(document).ready(function() {
	Quotas.init();
});