/**
 * Корзина
 */
var Cart = {

	deliveryChecked: false,
	itemsPrice: 0,
	init: function() {
		this.tickets = $('#js-tickets');
		this.price = $('#js-price');
		this.serv = $('#js-serv-price');
		this.deliveryBlock = $('.js-delivery-block');
		this.delivery = $('#js-delivery');
		this.total = $('#js-total');
		this.elDost = $('#elDostId');
		this.elDostPole = this.elDost.parents($('.it-block')).find($('.elDostPole'));
		this.deliveryPrice = parseInt(this.delivery.text());
		this.itemsPrice = parseInt(this.price.text()) + parseInt(this.serv.text());
		this.deliveryChecked = this.elDost.prop("checked");

		$('.delete').click(this.deleteItem);
		this.elDost.click(this.dostClick);
	},
	deleteItem: function() {
		var tr = $(this).closest('.js-row');
		var id = tr.attr('id');
		$.ajax({
			type: 'POST',
			url: '/ajax/cart.php',
			data: 'action=delete&id=' + id,
			success: function (data) {
				if (data.SUCCESS) {
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
							$('.elDost').hide();
						}
					}
				}
				if (data.CART) {
					Cart.tickets.text(data.CART.TICKETS);
					Cart.price.text(data.CART.PRICE);
					Cart.serv.text(data.CART.SERV_PRICE);
					Cart.itemsPrice = data.CART.PRICE + data.CART.SERV_PRICE;
					var d = Cart.deliveryChecked ? Cart.deliveryPrice : 0;
					Cart.total.text(data.CART.PRICE + data.CART.SERV_PRICE + d);
					TopCart.update(data.CART);
				}
			}
		});
	},
	dostClick: function () {
		Cart.deliveryChecked = Cart.elDost.prop("checked");
		if (Cart.deliveryChecked) {
			Cart.elDostPole.addClass('on');
			Cart.total.text(Cart.itemsPrice + Cart.deliveryPrice);
			Cart.deliveryBlock.show();
		}
		else {
			Cart.elDostPole.removeClass('on');
			Cart.total.text(Cart.itemsPrice);
			Cart.deliveryBlock.hide();
		}
	}
};

$(document).ready(function() {
	Cart.init();
});