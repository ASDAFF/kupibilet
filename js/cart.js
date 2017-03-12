/**
 * Корзина
 */
var Cart = {
	init: function() {
		$('.delete').click(this.deleteItem);
		this.emptyCart = $('.empty-cart');
	},
	deleteItem: function() {
		var tr = $(this).closest('.it-block');
		var id = tr.attr('id');
		$.ajax({
			type: 'POST',
			url: '/ajax/cart.php',
			data: 'action=delete&id=' + id,
			success: function (data) {
				if (data.SUCCESS) {
					var parent = tr.parent();
					var div = parent.closest('.run');
					tr.remove();
					if (!parent.children().length) {
						parent = div.parent();
						div.remove();
						if (!parent.children().length)
							Cart.emptyCart.show();
					}
				}
			}
		});
	}
};

$(document).ready(function() {
	Cart.init();
});