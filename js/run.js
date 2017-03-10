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


		this.ZalBox.on('mouseenter', '.elZal-item', this.hover);
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
		if (price)
			Run.priceRow.show();
		else
			Run.priceRow.hide();
		Run.ZalInf.appendTo($(this));
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
					if (data.ID)
						point.addClass('order');
				}
				else if (action == 'remove') {
					if (data.SUCCESS)
						point.removeClass('order');
				}
				if (typeof(data.CART) != 'undefined') {
					Run.cartCount.text(data.CART.COUNT);
					Run.cartPrice.text(data.CART.PRICE);
				}
			}
		});
	}
};

$(document).ready(function() {
	Run.init();
});