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

    //fix menu
    var headHeight = 350; // высота шапки
    $(window).on('scroll resize load', function() {
        if($(this).scrollTop() > headHeight)
            $('.elZal-top').addClass('fixed-menu');
        else
            $('.elZal-top').removeClass('fixed-menu', 600);
    });
    // Скролинг
    var elZal = $(".elZal"),
        elZalZoom = $(".elZalZoom"),
        elZalBtnPlus = $("#elZalBtnPlus"),
        elZalBtnMinus = $("#elZalBtnMinus"),
        zoom = 1,
        HeightSize = 400;

        $(window).on('scroll resize load', function() {
            if($(this).scrollTop() > HeightSize)
                elZalZoom.addClass('fixed');
            else
                elZalZoom.removeClass('fixed', 600);
        });


        elZalBtnPlus.click(function () {
            if(zoom < 1.6) {
                zoom = zoom+0.2;
                elZalBtnMinus.removeClass('none');
                elZalBtnPlus.removeClass('none');
            }else{elZalBtnPlus.addClass('none')}
            elZal.css("zoom",zoom);
        });
        elZalBtnMinus.click(function () {
            if(zoom > 0.8) {
                zoom = zoom-0.2;
            elZalBtnMinus.removeClass('none');
            elZalBtnPlus.removeClass('none');
            }else{elZalBtnMinus.addClass('none')}
            elZal.css("zoom",zoom);
        });


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

function engLog(title,text){
    var log = $("#engAjaxLog"),
        logItem = $("<div class='it-item'><div class='it-title'>"+title+"</div>"+text+"</div>");

    log.css('position','fixed').css('z-index','1000').css('bottom','0').css('right','0');

    logItem.appendTo(log);
    setTimeout(function(){
        logItem.fadeOut(300,function(){
            $(this).remove();
        });
    },4000);
}
