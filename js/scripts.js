$(function() {
    // Меню
    var pull 		= $('#engNav-btn');
    menu 		= $('nav ul');
    menuHeight	= menu.height();

    $(pull).on('click', function(e) {
        e.preventDefault();
        menu.slideToggle();
    });

    // Изменения размера экрана
    engBlockFix();

    // Дата
    var DP = $('#engDate-picter');
    if (DP.length) {
        DP.datepicker({
            beforeShowDay: function severalDates(date){
                var r = [false, ""];
                if (typeof(picterDates) != 'undefined') {
                    var dat = $.datepicker.formatDate("dd.mm.yy", date);
                    for (var i = 0, c = picterDates.length; i < c; i++)
                        if (dat == picterDates[i]) {
                            r = [true, "yellow"];
                            return r;
                        }
                }
                return r;
            },
            defaultDate: "+4d",
            onSelect: function (selectedDate) {
                if (typeof(Filters) != 'undefined' && Filters.inited)
                    Filters.dateClick(selectedDate);
                else
                    location.href = "/event/?d=" + selectedDate;
            }
        });
    }
} );


//  Перевод
( function( factory ) {
    factory( jQuery.datepicker );
}( function( datepicker ) {

    datepicker.regional.ru = {
        closeText: "Закрыть",
        prevText: "&#x3C;Пред",
        nextText: "След&#x3E;",
        currentText: "Сегодня",
        monthNames: [ "Январь","Февраль","Март","Апрель","Май","Июнь",
            "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь" ],
        monthNamesShort: [ "Янв","Фев","Мар","Апр","Май","Июн",
            "Июл","Авг","Сен","Окт","Ноя","Дек" ],
        dayNames: [ "воскресенье","понедельник","вторник","среда","четверг","пятница","суббота" ],
        dayNamesShort: [ "вск","пнд","втр","срд","чтв","птн","сбт" ],
        dayNamesMin: [ "Вс","Пн","Вт","Ср","Чт","Пт","Сб" ],
        weekHeader: "Нед",
        dateFormat: "dd.mm.yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: "" };
    datepicker.setDefaults( datepicker.regional.ru );

    return datepicker.regional.ru;

} ) );

function engBlockFix() {
    var rightBlockDetail = $('.elRight-filter');
    var rightBlock = $('.engBox-right');
    var centerBlock = $(".engBox-content");

    if($(document).width() < 1200) {
        rightBlockDetail.detach();
        centerBlock.prepend(rightBlockDetail);
    }
    if($(document).width() > 1200) {
        rightBlockDetail.detach();
        rightBlock.prepend(rightBlockDetail);
    }};

$(document).ready(function() {
    $( window ).resize(function() {engBlockFix();});

    $('.elSlider').owlCarousel({
        navigation : true, // показывать кнопки next и prev
        nav:true,
        navText: ["<span class='engIcon setIcon-slider-left'></span>","<span class='engIcon setIcon-slider-right'></span>"],

        slideSpeed : 300,
        paginationSpeed : 400,

        items : 1,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : false
    });
});
