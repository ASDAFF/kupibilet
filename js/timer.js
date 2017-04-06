var Timer = {
    element: '',
    time: '',

    init: function (element) {
        this.element = $(element);
        this.time = this.element.data('expired');
        if (this.time) {
            this.run();
        }
    },

    run: function () {
        var self = this;
        this.timeinterval = setInterval(function () {
            var t = self.countDown(self.time);
            var html = t.hours + ':' + t.minutes + ':' + t.seconds;
            self.element.html(html);
            if (t.total <= 0) {
                self.clear();
            }
        }, 1000);
    },

    clear: function () {
        clearInterval(this.timeinterval);
    },

    countDown: function (date) {
        var t = Date.parse(date) - Date.parse(new Date());
        var seconds = Math.floor((t / 1000) % 60);
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        var days = Math.floor(t / (1000 * 60 * 60 * 24));
        return {
            'total': t,
            'days': (days < 10) ? '0' + days : days,
            'hours': (hours < 10) ? '0' + hours : hours,
            'minutes': (minutes < 10) ? '0' + minutes : minutes,
            'seconds': (seconds<10) ? '0' + seconds : seconds
        };
    },

    update: function (time) {
        if (time == 'undefined') {
            this.time = this.element.data('expired');
        }else{
            this.time = time;
        }

        this.run();
    }
};

$(document).ready(function () {
    Timer.init('#reserve_timer');
});