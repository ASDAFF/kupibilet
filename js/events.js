/**
 * Панель фильтров
 */
var Filters = {
	inited: false,
	price_from: 0,
	price_to: 0,
	price_min: 0,
	price_max: 0,
	price: false,
	date: '',
	mfWidth: 0,
	curMfg: false,
	priceInputFl: false,
	init: function() {
		this.panel = $('#filters-panel');
		if (!this.panel.length)
			return;

		this.inited = true;
		this.catalogPath = this.panel.find('input[name=catalog_path]').val();
		this.separator = this.panel.find('input[name=separator]').val();
		this.q = this.panel.find('input[name=q]').val();
		this.groups = this.panel.find('.filter-group');
		this.cb = this.panel.find('input[type=checkbox]');
		this.ajaxCont = $('#catalog-list');
		this.bcCont = $('#bc');
		this.h1Cont = $('#h1');
		this.clearBtn = $('.filter-clear');

		this.priceInit();

		this.cb.click(this.checkboxClick);
		this.ajaxCont.on('click', '#current-filters a', this.urlClick);
		this.ajaxCont.on('click', '.pagination a', this.urlClick);
		this.bcCont.on('click', 'a', this.urlClick);
		this.clearBtn.on('click', this.urlClick);

		$(window).on('popstate', function (e) {
			var url = e.target.location;
			Filters.loadProducts(url, false);
		});
	},
	dateClick: function(date) {
		Filters.date = date;
		Filters.updateProducts();
	},
	priceInit: function() {
		this.priceGroup = $('.price-group');
		this.labelFrom = $('#slider-range-value1 b');
		this.labelTo = $('#slider-range-value2 b');
		this.inputFrom = this.priceGroup.find('.from');
		this.inputTo = this.priceGroup.find('.to');
		this.price_from = this.inputFrom.val();
		this.price_to = this.inputTo.val();
		this.price_min = this.priceGroup.data('min');
		this.price_max = this.priceGroup.data('max');
		this.priceLabelFrom1 = $('#slider-range-value1');
		this.priceLabelTo1 = $('#slider-range-value2');
		this.priceLabelFrom2 = $(".it-filter-money #slider-range span:first")
		this.priceLabelTo2 = $(".it-filter-money #slider-range span:last")

		this.priceSlider = $('#slider-range');
		this.priceSlider.slider({
			range: true,
			min: this.price_min,
			max: this.price_max,
			values: [this.price_from, this.price_to],
			step: 100,
			slide: this.priceSlide,
			stop: this.updateProducts
		});
	},
	correctSliderPositions: function(event, ui) {
		Filters.priceLabelFrom1.css("left", Filters.priceLabelFrom2.css("left"));
		Filters.priceLabelTo1.css("left", Filters.priceLabelTo2.css("left"));
	},
	priceSlide: function(event, ui) {
		Filters.labelFrom.text(ui.values[0]);
		Filters.labelTo.text(ui.values[1]);
		Filters.correctSliderPositions();
		Filters.inputFrom.val(ui.values[0]);
		Filters.inputTo.val(ui.values[1]);
		Filters.price_from = ui.values[0];
		Filters.price_to = ui.values[1];
	},
	priceCorrect: function(data) {
		Filters.price_from = data.FROM;
		Filters.price_to = data.TO;
		Filters.inputFrom.val(data.FROM);
		Filters.inputTo.val(data.TO);
		Filters.labelFrom.text(data.FROM);
		Filters.labelTo.text(data.TO);
		Filters.priceSlider.slider('values', [data.FROM, data.TO]);
		Filters.correctSliderPositions();
	},
	checkboxClick: function() {
		var input = $(this);
		Filters.updateCb(input);
	},
	updateCb: function(input) {
		var li = input.closest('li');
		var checked = input.prop('checked');
		if (checked)
			li.addClass('checked');
		else
			li.removeClass('checked');
		Filters.updateProducts();
	},
	updateProducts: function() {
		var url = Filters.catalogPath;
		Filters.groups.each(function() {
			var cb = $(this).find('input[type=checkbox]:checked');
			var part = '';
			cb.each(function() {
				if (part)
					part += Filters.separator;
				part += $(this).attr('name');
			});
			if (part)
				url += part + '/';
		});
		var params = '';
		if (Filters.q) {
			params += params ? '&' : '?';
			params += 'q=' + Filters.q;
		}
		if (Filters.price_from <= Filters.price_to) {
			if (Filters.price_from > Filters.price_min) {
				params += params ? '&' : '?';
				params += 'p-from=' + Filters.price_from;
			}
			if (Filters.price_to < Filters.price_max) {
				params += params ? '&' : '?';
				params += 'p-to=' + Filters.price_to;
			}
		}
		if (Filters.date) {
			params += params ? '&' : '?';
			params += 'd=' + Filters.date;
		}
		url += params;
		Filters.loadProducts(url, true);

		return false;
	},
	loadProducts: function(url, setHistory) {
		$.post(url, {
			'mode': 'ajax'
		}, function (resp) {
			Filters.ajaxCont.html(resp.HTML);
			Filters.bcCont.html(resp.BC);
			Filters.h1Cont.html(resp.H1);
			for (var i in resp.FILTERS) {
				if (i == 'PRICE') {
					Filters.priceCorrect(resp.FILTERS[i]);
				}
				else {
					var cnt = resp.FILTERS[i][0];
					var checked = resp.FILTERS[i][1];
					var cb = Filters.panel.find('input[name=' + i + ']');
					var li = cb.closest('li');
					cb.prop('checked', checked);
					if (checked)
						li.addClass('checked');
					else
						li.removeClass('checked');
					if (cnt) {
						cb.prop('disabled', false);
						li.removeClass('disabled');
						//li.stop().slideDown();
					}
					else {
						cb.prop('disabled', true);
						li.addClass('disabled');
						//li.stop().slideUp();
					}
					cb.siblings('i').text(cnt);
				}
			}

			document.title = resp.TITLE;
			if (setHistory)
				history.pushState('', resp.TITLE, url);

			Filters.q = resp.SEARCH;

			return false;

		})
	},
	urlClick: function() {
		var url = $(this).attr('href');
		if (url == '/')
			return true;

		Filters.loadProducts(url, true);

		return false;
	}
};

$(document).ready(function() {
	Filters.init();

});

