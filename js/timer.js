var Timer = {
	init: function () {
		this.timers = $('.timer');
		this.pool = {};
		this.expiredDate = {};
		this.setTimer();
	},

	setTimer: function () {

		for (var i = 0; i < Timer.timers.length; i++) {
			var time = $(Timer.timers[i]).data('expired');
			var id = $(Timer.timers[i]).attr('id');
			Timer.pool[id] = time;
			Timer.expiredDate[id] = new Date();
			Timer.expiredDate[id].setSeconds(Timer.expiredDate[id].getSeconds() + time);
		}

		setInterval(Timer.tr, 500);
	},

	tr: function () {
		for (var i = 0; i < Timer.timers.length; i++) {
			var id = $(Timer.timers[i]).attr('id');
			Timer.tick(id, Timer.expiredDate[id])
		}
	},

	tick: function (id, time) {
		var t = Math.floor((time - new Date()) / 1000);
		var html = "00:00:00";
		if (t > 0) {
			var seconds = Math.floor(t % 60);
			var minutes = Math.floor((t / 60) % 60);
			var hours = Math.floor((t / 3600) % 24);
			hours = (hours < 10) ? '0' + hours : hours;
			minutes = (minutes < 10) ? '0' + minutes : minutes;
			seconds = (seconds < 10) ? '0' + seconds : seconds;
			html = hours + ':' + minutes + ':' + seconds;
		}
		$('.timer#' + id).html(html);
	}
};

$('document').ready(function () {
	Timer.init();
});
