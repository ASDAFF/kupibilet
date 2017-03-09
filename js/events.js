/**
 * Панель фильтров
 */
var Filters = {
	price_from: 0,
	price_to: 0,
	price_min: 0,
	price_max: 0,
	price: false,
	mfWidth: 0,
	curMfg: false,
	priceInputFl: false,
	init: function() {
		this.panel = $('#filters-panel');
		if (!this.panel.length)
			return;

		this.catalogPath = this.panel.find('input[name=catalog_path]').val();
		this.separator = this.panel.find('input[name=separator]').val();
		this.q = this.panel.find('input[name=q]').val();
		this.groups = this.panel.find('.filter-group');
		this.cb = this.panel.find('input[type=checkbox]');
		this.ajaxCont = $('#catalog-list');
		this.bcCont = $('#bc');
		this.h1Cont = $('#h1');

		this.priceInit();
		this.dateInit();

		this.cb.click(this.checkboxClick);
		this.ajaxCont.on('click', '#current-filters a', this.urlClick);
		this.ajaxCont.on('click', '.pagination a', this.urlClick);
		this.bcCont.on('click', 'a', this.urlClick);
		

		$(window).on('popstate', function (e) {
			var url = e.target.location;
			Filters.loadProducts(url, false);
		});
		
		$('.filter-clear').on('click', function (e) {
			var url = "/event/";
			Filters.loadProducts(url, false);
			history.pushState('', '', url);
		});

		$('.filter-add').on('click', function (e) {
			var url = "/event/";
			Filters.loadProducts(url, false);
			history.pushState('', '', url);
		});

		$('#engDate-picter').datepicker({
            beforeShowDay: severalDates,
            defaultDate: "+4d",
            onSelect: function (selectedDate) {
                //location.href = "/event/?d-from=" + selectedDate; // Переход
				var url = '/event/?d-from=' + selectedDate;
				Filters.loadProducts(url, false);
				history.pushState('', '', url);
            }
        });
	},
	priceInit: function() {
		this.priceGroup = $('.price-group');
		this.inputFrom = this.priceGroup.find('.from');
		this.inputTo = this.priceGroup.find('.to');
		this.price_from = this.inputFrom.val();
		this.price_to = this.inputTo.val();
		this.price_min = this.priceGroup.data('min');
		this.price_max = this.priceGroup.data('max');

		if (this.price_min == this.price_max)
			return;

		this.inputFrom.on('change', Filters.priceChange);
		this.inputTo.on('change', Filters.priceChange);
	},
	priceChange: function() {
		Filters.price_from = Filters.inputFrom.val();
		Filters.price_to = Filters.inputTo.val();
		//Filters.updateProducts();
	},
	priceCorrect: function(data) {
		Filters.price_from = data.FROM;
		Filters.price_to = data.TO;
		Filters.inputFrom.val(data.FROM);
		Filters.inputTo.val(data.TO);
	},
	dateInit: function() {
		this.dateGroup = $('.date-group');
		this.dateFrom = this.dateGroup.find('.from');
		this.dateTo = this.dateGroup.find('.to');
		this.date_from = this.dateFrom.val();
		this.date_to = this.dateTo.val();
		this.date_min = this.dateGroup.data('min');
		this.date_max = this.dateGroup.data('max');

		if (this.date_min == this.date_max)
			return;

		this.dateFrom.on('change', Filters.dateChange);
		this.dateTo.on('change', Filters.dateChange);
	},
	dateChange: function() {
		Filters.date_from = Filters.dateFrom.val();
		Filters.date_to = Filters.dateTo.val();
		//Filters.updateProducts();
	},
	dateCorrect: function(data) {
		Filters.date_from = data.FROM;
		Filters.date_to = data.TO;
		Filters.dateFrom.val(data.FROM);
		Filters.dateTo.val(data.TO);
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
		if (Filters.date_from != Filters.date_min) {
			params += params ? '&' : '?';
			params += 'd-from=' + Filters.date_from;
		}
		if (Filters.date_to != Filters.date_max) {
			params += params ? '&' : '?';
			params += 'd-to=' + Filters.date_to;
		}
		url += params;
		Filters.loadProducts(url, true);
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
				else if (i == 'DATE') {
					Filters.dateCorrect(resp.FILTERS[i]);
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
	    console.log("a "+url);

		if (url == '/')
			return true;

		Filters.loadProducts(url, true);

		return false;
	}
};

$(document).ready(function() {
	Filters.init();
    elList();

});

function elList(){
	$('.elList').masonry({
		// options...
		itemSelector: '.it-item',
		columnWidth: 395
	});
	console.log("Сотируем");
}
