/**
 * Показ, добавление в корзину
 */
var Run = {
	hoverItem: false,
	init: function() {
		if (typeof(ZalArray) == 'undefined')
			return;

		this.ZalArray = ZalArray;
		this.Zal = $("#elZal");
		this.ZalBox = $("#elZal-box");
		this.pHidden = $('#popup-hidden');
		this.ZalInf = $(".elZal-inf");
		this.infTitle = this.ZalInf.find('.elZal-inf-title');
		this.infRow = this.ZalInf.find('.elZal-inf-set');
		this.infNum = this.ZalInf.find('.elZal-inf-number');
		this.infPrice = this.ZalInf.find('.elZal-inf-money');
		this.infBtn = this.ZalInf.find('.elZal-inf-btn');

		this.priceRow = this.ZalInf.find('.priceRow');
		this.btnRow = this.ZalInf.find('.btnRow');
		this.eventId = this.Zal.data('event');
		this.runId = this.Zal.data('run');
		this.elZalScenBox = $('.elZalScenBox');

		this.ZalBox.on('mouseenter', '.elZal-item', this.hover);
		this.ZalBox.on('mouseleave', '.elZal-item', this.leave);
		this.ZalBox.on('click', '.elZal-point.on', this.click);
		this.elZalScenBox.find('.it-item').on('click', this.itClick);
		this.infBtn.on('click', this.popupBtnClick);


		var parter = this.elZalScenBox.find('.it-item[data-zal="Партер"]');
		if (!parter.length)
			parter = this.elZalScenBox.find('.it-item[data-zal="Партер средний"]');
		if (parter.length)
			parter.click();
	},
	hover: function() {
		var item = $(this);
		var id = item.attr('id');
		var zalItem = Run.ZalArray[id];
		var price = 0;
		if (zalItem[6])
			price = zalItem[6];

		Run.infTitle.text(zalItem[3]);
		Run.infRow.text(zalItem[4]);
		Run.infNum.text(zalItem[5]);
		Run.infPrice.text(price);

		var point = item.find('.elZal-point');

		if (price && !point.hasClass('off'))
            Run.priceRow.show();
		else
            Run.priceRow.hide();

		if (!price || point.hasClass('off') || point.hasClass('order'))
			Run.btnRow.hide();
		else
			Run.btnRow.show();

		var pos = item.offset();
		var left = pos.left - 68;
		var top = pos.top + 36;
		Run.ZalInf.css({
			left: "" + left + "px",
			top: "" + top + "px"
		});
		Run.ZalInf.show();
		Run.hoverItem = item;
	},
	leave: function() {
		Run.ZalInf.hide();
	},
	click: function() {
		var point = $(this);
		var item = point.parent();
		Run.send(point, item);
	},
	send: function(point, item) {
		var id = item.attr('id');
		var action = point.hasClass('order') ? 'remove' : 'add';
		$.ajax({
			type: 'POST',
			url: '/ajax/cart.php',
			data: 'action=' + action + '&eid=' + Run.eventId + '&rid=' + Run.runId + '&id=' + id,
			success: function (data) {
				if (action == 'add') {
					if (data.ID) {
						point.addClass('order');
						engLog('Место забронировано','<a href="/personal/cart/">Оформить заказ</a> продолжить покупки');
					}
				}
				else if (action == 'remove') {
					if (data.SUCCESS)
						point.removeClass('order');
				}
				if (typeof(data.CART) != 'undefined')
					TopCart.update(data.CART);
			}
		});
	},
	popupBtnClick: function() {
		var item = Run.hoverItem;
		if (!Run.hoverItem)
			return;

		var point = item.children('.elZal-point');
		Run.send(point, item);
	},
	itClick: function() {
		Run.tZalMin_top = 99999;
		Run.tZalMin_left = 99999;
		Run.tZalMax_top = 0;
		Run.tZalMax_left = 0;

		Run.itUpdate($(this));
		Run.ZalBox.css("max-width", Run.tZalMax_left - Run.tZalMin_left + 50);
		Run.ZalBox.css("max-height", Run.tZalMax_top - Run.tZalMin_top + 50);

		// Двигаемся к залам
		$('html , body').animate({scrollTop: ($(Run.elZalScenBox).offset().top)-50}, 1000);
	},
	itUpdate: function(item) {
		Run.zoneName = item.attr('data-zal');
		Run.elZalScenBox.find('.it-item').removeClass('active');
		item.addClass('active');

		Run.ZalBox.children().hide();
		// Перебераем массив и определяем сколько нужно порезать cлева и справа
		for (var i in Run.ZalArray) {
			if (Run.ZalArray[i][3] === Run.zoneName) {
				if (Run.tZalMin_top > Run.ZalArray[i][1])
					Run.tZalMin_top = Run.ZalArray[i][1];
				if (Run.tZalMin_left > Run.ZalArray[i][0])
					Run.tZalMin_left = Run.ZalArray[i][0];
				if (Run.tZalMax_top < Run.ZalArray[i][1])
					Run.tZalMax_top = Run.ZalArray[i][1];
				if (Run.tZalMax_left < Run.ZalArray[i][0])
					Run.tZalMax_left = Run.ZalArray[i][0];
			}
		}

		// Перебераем массив
		for (var i in Run.ZalArray) {
			if (Run.ZalArray[i][3] === Run.zoneName) {
				var tmp = Run.ZalBox.children('#' + i);
				tmp.css({
					top: '' + (Run.ZalArray[i][1] - Run.tZalMin_top) + 'px',
					left: '' + (Run.ZalArray[i][0] - Run.tZalMin_left) + 'px'
				});
				tmp.show();
			}
		}

	}
};

$(document).ready(function() {
	Run.init();
});