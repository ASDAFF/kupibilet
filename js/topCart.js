/**
 * Корзина в шапке
 */
var TopCart = {
	init: function() {
		this.cart = $('.elHeader .elCart');
		this.count = this.cart.find('#current_cart_count');
		this.price = this.cart.find('#current_cart_price');
		this.timer = this.cart.find('#reserve_timer');
		this.expired = this.timer.data('expired');
		this.updateTimer();
	},

	update: function(data) {
		TopCart.count.text(data.COUNT);
		TopCart.price.text(data.PRICE);
		TopCart.expired = data.EXPIRED;
		TopCart.timer.data('expired', data.EXPIRED);
		TopCart.updateTimer();
	},

	updateTimer: function () {
		if (TopCart.expired <= 0)
			TopCart.clear();
		else {
			TopCart.expiredDate = new Date();
			TopCart.expiredDate.setSeconds(TopCart.expiredDate.getSeconds() + TopCart.expired);

			if (!TopCart.timeinterval)
				TopCart.timeinterval = setInterval(TopCart.tick, 500);
		}
	},

	tick: function() {
		var t = Math.floor((TopCart.expiredDate - new Date()) / 1000);
		var seconds = Math.floor(t % 60);
		var minutes = Math.floor((t / 60) % 60);
		var hours = Math.floor((t / 3600) % 24);
		hours = (hours < 10) ? '0' + hours : hours;
		minutes = (minutes < 10) ? '0' + minutes : minutes;
		seconds = (seconds < 10) ? '0' + seconds : seconds;

		var html = hours + ':' + minutes + ':' + seconds;
		TopCart.timer.html(html);

		if (t.total <= 0)
			TopCart.clear();
	},

	clear: function () {
		this.timer.html('');
		if (TopCart.timeinterval) {
			clearInterval(this.timeinterval);
			TopCart.timeinterval = 0;
		}
	}
};

$(document).ready(function() {
	TopCart.init();
});