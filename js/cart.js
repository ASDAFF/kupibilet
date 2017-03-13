/**
 * Корзина
 */
var Cart = {
	init: function() {
		this.tickets = $('#js-tickets');
		this.price = $('#js-price');
		this.serv = $('#js-serv-price');
		this.total = $('#js-total');

		$('.delete').click(this.deleteItem);
	},
	deleteItem: function() {
		var tr = $(this).closest('.js-row');
		var id = tr.attr('id');
		$.ajax({
			type: 'POST',
			url: '/ajax/cart.php',
			data: 'action=delete&id=' + id,
			success: function (data) {
				//if (data.SUCCESS) {
					var parent = tr.parent();
					var div = parent.closest('.js-run');
					tr.remove();
					if (!parent.children('.js-row').length) {
						parent = div.parent();
						div.remove();
						if (!parent.children('.js-run').length) {
							$('.empty-cart').show();
							$('.elBasket-footer').hide();
							$('.elBasket-form').hide();
						}
					}
				//}
				if (data.CART) {
					Cart.tickets.text(data.CART.TICKETS);
					Cart.price.text(data.CART.PRICE);
					Cart.serv.text(data.CART.SERV_PRICE);
					Cart.total.text(data.CART.PRICE + data.CART.SERV_PRICE);
				}
			}
		});
	}
};

$(document).ready(function() {
	Cart.init();
});