/**
 * Конструктор схемы мест зала
 */
var Scheme = {
	init: function() {
		this.Zal = $("#elZal");
		this.ZalBox = $("#elZal-box");
		this.pHidden = $('#popup-hidden');
		this.ZalInf = $(".elZal-inf");
		this.infTitle = this.ZalInf.find('.elZal-inf-title');
		this.infRow = this.ZalInf.find('.elZal-inf-set');
		this.infNum = this.ZalInf.find('.elZal-inf-number');
		this.optTitle = $('input[name="title"]');
		this.optRow = $('input[name="row"]');
		this.optNum = $('input[name="num"]');
		this.optX = $('input[name="x"]');
		this.optY = $('input[name="y"]');
		this.optRotate = $('input[name="rotate"]');
		this.popupCb = $('input[name="popup"]');
		this.newBtn = $('input.new');
		this.saveBtn = $('input.save');
		this.delBtn = $('input.del');
		this.showPopup = this.popupCb.prop('checked');
		this.currentId = 0;
		this.dragRegime = 0;
		this.dragItem = false;
		this.dragId = 0;
		this.dX = 0;
		this.dY = 0;
		this.lastId = 0;
		this.saveTimerId = 0;
		this.elementId = this.Zal.data('id');
		this.body = $('body');
		this.backLink = $('.theater-info a');

		if (typeof(ZalArray) == 'undefined')
			ZalArray = {};
		for (var i in ZalArray) {
			this.lastId = i;
			if (ZalArray.hasOwnProperty(i)) {
				var zalItem = ZalArray[i];
				this.addItem(zalItem, i);
			}
		}
		this.clearCurrent();

		this.ZalBox.on('mouseenter', '.elZal-item', this.hover);
		this.ZalBox.on('mousedown', '.elZal-point', this.mouseDown);
		this.body.on('mouseup', this.mouseUp);
		this.body.on('mousemove', this.mouseMove);
		this.Zal.on('mouseup', this.zalMouseUp);
		this.optTitle.on('input', this.titleInput);
		this.optRow.on('input', this.rowInput);
		this.optNum.on('input', this.numInput);
		this.optX.on('input', this.xInput);
		this.optY.on('input', this.yInput);
		this.optRotate.on('input', this.rotateInput);
		this.newBtn.on('click', this.newBtnClick);
		this.saveBtn.on('click', this.saveAjax);
		this.delBtn.on('click', this.delBtnClick);
		this.popupCb.on('click', this.popupCbClick);
		this.backLink.on('click', this.backClick);
	},
	hover: function() {
		if (Scheme.showPopup) {
			var item = $(this);
			var id = item.attr('id');
			var zalItem = ZalArray[id];
			Scheme.infTitle.text(zalItem[3]);
			Scheme.infRow.text(zalItem[4]);
			Scheme.infNum.text(zalItem[5]);
			Scheme.ZalInf.appendTo($(this));
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

		Scheme.ZalBox.append(tmp);

		return tmp;
	},
	save: function() {
		if (Scheme.saveTimerId)
			clearTimeout(Scheme.saveTimerId);
		Scheme.saveTimerId = setTimeout(Scheme.saveAjax, 60000);
		Scheme.saveBtn.removeClass('disabled');
	},
	saveAjax: function() {
		if (Scheme.saveBtn.hasClass('disabled'))
			return;
		if (Scheme.saveTimerId)
			clearTimeout(Scheme.saveTimerId);

		var data = JSON.stringify(ZalArray);
		$.ajax({
			type: 'POST',
			url: '/ajax/save_scheme.php?ID=' + Scheme.elementId,
			data: data,
			dataType: 'json',
			contentType: 'application/json; charset=utf-8',
			success: function () {
				Scheme.saveBtn.addClass('disabled');
			}
		});
	},
	mouseDown: function(e) {
		var item = $(this).parent();
		var id = item.attr('id');
		var zalItem = ZalArray[id];
		Scheme.optTitle.val(zalItem[3]);
		Scheme.optRow.val(zalItem[4]);
		Scheme.optNum.val(zalItem[5]);
		Scheme.optX.val(zalItem[0]);
		Scheme.optY.val(zalItem[1]);
		Scheme.optRotate.val(zalItem[2]);
		Scheme.currentId = id;
		Scheme.dragId = id;
		Scheme.dragItem = item;
		Scheme.delBtn.removeClass('disabled');
		Scheme.ZalBox.find('.current').removeClass('current');
		item.addClass('current');


		if (!e.altKey) {
			Scheme.dragRegime = 1;
			var pos = Scheme.dragItem.position();
			Scheme.dX = e.pageX - pos.left;
			Scheme.dY = e.pageY - pos.top;
		}
		else {
			Scheme.dragRegime = 2;
			Scheme.dX = e.pageX + e.pageY - zalItem[2];
		}
	},
	mouseUp: function(e) {
		if (Scheme.dragRegime == 1) {
			var left = e.pageX - Scheme.dX;
			var top = e.pageY - Scheme.dY;

			if (left < 0 || top < 0)
			{
				for (var i in ZalArray) {
					if (ZalArray.hasOwnProperty(i)) {
						var zalItem = ZalArray[i];
						if (left < 0)
							zalItem[0] = zalItem[0] - left;
						if (top < 0)
							zalItem[1] = zalItem[1] - top;
						var div = $('.elZal-item#' + i);
						div.css({
							left: "" + zalItem[0] + "px",
							top: "" + zalItem[1] + "px"
						});
					}
				}
				Scheme.save();
			}
		}
		Scheme.dragRegime = 0;
		Scheme.dragItem = false;

		return false;
	},
	mouseMove: function(e) {
		if (!Scheme.dragRegime || !Scheme.dragItem)
			return false;

		e.stopPropagation();
		var target = e.target;
		if (target.getAttribute('unselectable') == 'on')
			target.ownerDocument.defaultView.getSelection().removeAllRanges();

		var zalItem = ZalArray[Scheme.dragId];
		if (Scheme.dragRegime == 1) {
			var left = e.pageX - Scheme.dX;
			var top = e.pageY - Scheme.dY;

			Scheme.dragItem.css({
				left: "" + left + "px",
				top: "" + top + "px"
			});

			zalItem[0] = parseInt(left);
			zalItem[1] = parseInt(top);
			Scheme.optX.val(zalItem[0]);
			Scheme.optY.val(zalItem[1]);
		}
		else if (Scheme.dragRegime == 2) {
			var rotate = e.pageX + e.pageY - Scheme.dX;
			var val = 'rotate(' + rotate + 'deg)';
			Scheme.dragItem.children('.elZal-point').css({
				'-moz-transform': val,
				'-ms-transform': val,
				'-webkit-transform': val,
				'-o-transform': val,
				'transform': val
			});

			zalItem[2] = parseInt(rotate);
			Scheme.optRotate.val(zalItem[2]);
		}

		Scheme.save();

		return false;
	},
	zalMouseUp: function(e) {
		if (!$(e.target).is('.elZal-point')) {
			if (Scheme.currentId) {
				Scheme.ZalBox.find('.current').removeClass('current');
				Scheme.currentId = 0;
				Scheme.clearCurrent();
			}
		}
	},
	titleInput: function() {
		if (!Scheme.currentId)
			return false;

		var zalItem = ZalArray[Scheme.currentId];
		zalItem[3] = $(this).val();
		Scheme.save();
	},
	rowInput: function() {
		if (!Scheme.currentId)
			return false;

		var zalItem = ZalArray[Scheme.currentId];
		zalItem[4] = $(this).val();
		Scheme.save();
	},
	numInput: function() {
		if (!Scheme.currentId)
			return false;

		var zalItem = ZalArray[Scheme.currentId];
		zalItem[5] = $(this).val();
		var div = $('.elZal-item#' + Scheme.currentId + ' .elZal-point');
		div.text(zalItem[6]);

		Scheme.save();
	},
	xInput: function() {
		if (!Scheme.currentId)
			return false;

		var zalItem = ZalArray[Scheme.currentId];
		zalItem[0] = parseInt($(this).val());
		var div = $('.elZal-item#' + Scheme.currentId);
		div.css({
			left: "" + zalItem[0] + "px"
		});
		Scheme.save();
	},
	yInput: function() {
		if (!Scheme.currentId)
			return false;

		var zalItem = ZalArray[Scheme.currentId];
		zalItem[1] = parseInt($(this).val());
		var div = $('.elZal-item#' + Scheme.currentId);
		div.css({
			top: "" + zalItem[1] + "px"
		});
		Scheme.save();
	},
	rotateInput: function() {
		if (!Scheme.currentId)
			return false;

		var zalItem = ZalArray[Scheme.currentId];
		zalItem[2] = parseInt($(this).val());
		var div = $('.elZal-item#' + Scheme.currentId);
		var val = 'rotate(' + zalItem[2] + 'deg)';
		div.children('.elZal-point').css({
			'-moz-transform': val,
			'-ms-transform': val,
			'-webkit-transform': val,
			'-o-transform': val,
			'transform': val
		});
		Scheme.save();
	},
	newBtnClick: function() {
		Scheme.lastId++;
		var zalItem = [];
		if (Scheme.currentId) {
			var zI = ZalArray[Scheme.currentId];
			zalItem = [zI[0]+25,zI[1],zI[2],zI[3],zI[4],parseInt(zI[5])+1];
		}
		else {
			zalItem = [20,20,0,'Партер',1,1];
		}

		var item = Scheme.addItem(zalItem, Scheme.lastId);
		Scheme.ZalBox.find('.current').removeClass('current');
		item.addClass('current');

		ZalArray[Scheme.lastId] = zalItem;
		Scheme.optTitle.val(zalItem[3]);
		Scheme.optRow.val(zalItem[4]);
		Scheme.optNum.val(zalItem[5]);
		Scheme.optX.val(zalItem[0]);
		Scheme.optY.val(zalItem[1]);
		Scheme.optRotate.val(zalItem[2]);
		Scheme.currentId = Scheme.lastId;
		Scheme.save();
	},
	delBtnClick: function() {
		if (!Scheme.currentId)
			return false;

		delete ZalArray[Scheme.currentId];
		var div = $('.elZal-item#' + Scheme.currentId);
		div.remove();

		Scheme.currentId = 0;
		Scheme.delBtn.addClass('disabled');
		Scheme.ZalBox.find('.current').removeClass('current');
		Scheme.clearCurrent();
		Scheme.save();
	},
	clearCurrent: function() {
		Scheme.optTitle.val('');
		Scheme.optRow.val('');
		Scheme.optNum.val('');
		Scheme.optX.val('');
		Scheme.optY.val('');
		Scheme.optRotate.val('');
	},
	popupCbClick: function() {
		Scheme.showPopup = Scheme.popupCb.prop('checked');
		if (!Scheme.showPopup)
			Scheme.ZalInf.appendTo(Scheme.pHidden);
	},
	backClick: function() {
		Scheme.saveAjax();
		return true;
	}
};

$(document).ready(function() {
	Scheme.init();
});