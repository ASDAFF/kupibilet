/**
 * Показ, добавление в корзину
 */
var Run = {
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
		this.priceRow = this.ZalInf.find('.priceRow');
		this.eventId = this.Zal.data('event');
		this.runId = this.Zal.data('run');
		this.cartCount = $('#current_cart_count');
		this.cartPrice = $('#current_cart_price');
		this.cartTimer = $('#reserve_timer');

		this.ZalBox.on('mouseenter', '.elZal-item', this.hover);
		this.ZalBox.on('mouseleave', '.elZal-item', this.leave);
		this.ZalBox.on('click', '.elZal-point.on', this.click);
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

		if (price && !item.find('.elZal-point').hasClass('off'))
			Run.priceRow.show();
		else
			Run.priceRow.hide();

		var pos = item.offset();
		var left = pos.left - 68;
		var top = pos.top + 36;
		Run.ZalInf.css({
			left: "" + left + "px",
			top: "" + top + "px"
		});
		Run.ZalInf.show();
	},
	leave: function() {
		Run.ZalInf.hide();
	},
	click: function() {
		var point = $(this);
		var item = point.parent();
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
						engLog('Место забронировано','<a href="/personal/cart/">Перейти в корзину</a> продолжить покупки');
					}
				}
				else if (action == 'remove') {
					if (data.SUCCESS)
						point.removeClass('order');
				}
				if (typeof(data.CART) != 'undefined') {
					Run.cartCount.text(data.CART.COUNT);
					Run.cartPrice.text(data.CART.PRICE);
					Run.cartTimer.data('expired', data.CART.EXPIRED);
					//timer.update();
				}
			}
		});
	}
};

$(document).ready(function() {
	Run.init();
});