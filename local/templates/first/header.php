<!DOCTYPE html>
<?/*<html>
<head><?

	/** @var CMain $APPLICATION */
	/** @var CUser $USER */

	?>
	<?/*<title><?$APPLICATION->ShowTitle();?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?

	$assets = \Bitrix\Main\Page\Asset::getInstance();

	$assets->addJs('/js/jquery.js');
	$assets->addJs('/js/events.js');

	$APPLICATION->ShowHead();
	?>
</head>
<body><?

$APPLICATION->ShowPanel();

?>
<div class="navbar">
	<hr />
	header
	<a href="/">Главная</a>
	<a href="/bitrix/admin">(Админка)</a>
	<hr />
</div>
<div><?

	$APPLICATION->IncludeComponent('bitrix:breadcrumb', '', Array());

	?>
	<h1 id="h1"><? $APPLICATION->ShowTitle(false, false); ?></h1>
	<div id="content">*/


define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);
require_once ROOT_DIR . "/Funcs.php";
?>

<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <? $APPLICATION->ShowHead(); ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <?
    $assets = \Bitrix\Main\Page\Asset::getInstance();
    $assets->addJs('/js/jquery.js');
    $assets->addJs('/js/events.js');
    $APPLICATION->ShowHead();
?>

	<link href="js/owl/owl.carousel.min.css" rel="stylesheet">
    <link href="js/owl/owl.theme.default.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css">
    <link href="js/fancybox/jquery.fancybox.css" rel="stylesheet">

    <script src="js/jquery.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="/js/jquery-ui/jquery-ui.js"></script>
    <script src="js/owl/owl.carousel.js"></script>
    <script src="js/owl/owl.carousel.min.js"></script>
    <script src="js/fancybox/jquery.fancybox.js"></script>
    <script src="https://unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.js"></script>
    <script src="https://unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.min.js"></script>


    <?/*
    $assets->addCss('/js/owl/owl.carousel.min.css');
    $assets->addCss('/js/owl/owl.theme.default.min.css');
    $assets->addCss('/js/jquery-ui/jquery-ui.css');
    $assets->addCss('/js/fancybox/jquery.fancybox.css');

    CJSCore::Init();
    $assets->addJs('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
    $assets->addJs('/js/jquery-ui/jquery-ui.js');
    $assets->addJs('/js/owl/owl.carousel.js');
    $assets->addJs('/js/owl/owl.carousel.min.js');
    $assets->addJs('/js/fancybox/jquery.fancybox.js');
    $assets->addJs('https://unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.js');
    $assets->addJs('https://unpkg.com/masonry-layout@4.1/dist/masonry.pkgd.min.js');*/
    ?>



    <link href="/css/style.css" rel="stylesheet">
        <script>

            $(document).ready(function() {
                $('.elSlider').owlCarousel({
                    navigation : true, // показывать кнопки next и prev
                    nav:true,
                    navText: ["<img src='images/slider/left.png'>","<img src='images/slider/right.png'>"],

                    slideSpeed : 300,
                    paginationSpeed : 400,

                    items : 1,
                    itemsDesktop : false,
                    itemsDesktopSmall : false,
                    itemsTablet: false,
                    itemsMobile : false
                });
                $(".eng-popap-btn").fancybox({
                    maxWidth	: 800,
                    maxHeight	: '100%',
                    fitToView	: false,
                    padding     : 0,
                    width		: '400',
                    height		: '600',
                    autoSize	: true,
                    closeClick	: false,
                });
            });
            $(function() {
                var pull 		= $('#engNav-btn');
                menu 		= $('nav ul');
                menuHeight	= menu.height();

                $(pull).on('click', function(e) {
                    e.preventDefault();
                    menu.slideToggle();
                });

                $(window).resize(function(){
                    var w = $(window).width();
                    if(w > 320 && menu.is(':hidden')) {
                        menu.removeAttr('style');
                    }
                });
            });
            $( function() {

                $.datepicker.setDefaults($.datepicker.regional['ru']);
                $('#engDate-picter').datepicker({
                    defaultDate: "+5y"
                });
            } );
//           Перевод
            ( function( factory ) {
                if ( typeof define === "function" && define.amd ) {

                    // AMD. Register as an anonymous module.
                    define( [ "../widgets/datepicker" ], factory );
                } else {

                    // Browser globals
                    factory( jQuery.datepicker );
                }
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


        </script>
</head>
<body>
	<? $APPLICATION->ShowPanel(); ?>
<header class="elHeader">
    <div class="engBox">
        <div class="engBox-3 engPl-6 engPl-cssText-center engMb ">
            <div class="elHeader-logo">
                <a href="/">
                    <img src="/images/logo.png">
                </a>
            </div>
        </div>
        <div class="engBox-5  engPl-none cssText-center cssVertical-center">
            <div class="elHeader-text">
			<?$APPLICATION->IncludeFile(
				SITE_DIR."/include/main/head.php",
				array(),
				array(
					"MODE" => "text"
				)
			); ?>
            	</div>
        </div>
        <div class="engBox-4 engPl-6 engMb">
            <div class="elHeader-phone cssText-right engPl-cssText-center">
                <b>
                <?$APPLICATION->IncludeFile(
					SITE_DIR."/include/main/tel.php",
					array(),
					array(
						"MODE" => "text"
					)
				); ?>
				</b>
                <span>
                	<?$APPLICATION->IncludeFile(
						SITE_DIR."/include/main/address.php",
						array(),
						array(
							"MODE" => "text"
						)
					); ?>
			</span>
            </div>
            <div class="elHeader-user cssText-right engPl-cssText-center">
                <a href="" class="cssBorderRadius">Вход</a>
                <a href="" class="cssBorderRadius">Регистрация</a>
            </div>
        </div>
    </div>
    <div class="elHeader-menu-full cssBg-red">
        <nav class="elHeader-menu engBox" id="engNav">
            <?/*<ul style="padding-left:0">
                <li><a href="">Главная</a></li>
                <li><a href="">Концерты</a></li>
                <li><a href="">Спектакли</a></li>
                <li><a href="">Фестивали</a></li>
                <li><a href="">Опера</a></li>
                <li><a href="">Детям</a></li>
            </ul>*/?>

            <?$APPLICATION->IncludeComponent("bitrix:menu", "main", Array(
				"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
					"CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
					"DELAY" => "N",	// Откладывать выполнение шаблона меню
					"MAX_LEVEL" => "1",	// Уровень вложенности меню
					"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
					"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
					"MENU_CACHE_TYPE" => "N",	// Тип кеширования
					"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
					"ROOT_MENU_TYPE" => "top",	// Тип меню для первого уровня
					"USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
					"COMPONENT_TEMPLATE" => ".default"
				),
				false
			);?>
			
            <a id="engNav-btn">Меню</a>
        </nav>
    </div>
<?$url = $_SERVER['PHP_SELF'];
$uri = explode('/', $url);
//print_r($uri)?>
<?if(!Funcs::$uri[0]):?>

<div class="engBox">
        <div class="elSlider">
            <?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"banner", 
	array(
		"ADD_ELEMENT_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "3",
		"IBLOCK_TYPE" => "main",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "PRICE",
			1 => "DATE",
			2 => "LOCATION",
			3 => "IN_BANNER",
			4 => "PRICE_TO",
			5 => "",
		),
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PREVIEW_TRUNCATE_LEN" => "",
		"SEF_MODE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_RATING" => "N",
		"USE_RSS" => "N",
		"USE_SEARCH" => "N",
		"USE_SHARE" => "N",
		"COMPONENT_TEMPLATE" => "banner",
		"SEF_FOLDER" => "",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "#ELEMENT_ID#/",
		)
	),
	false
);?>
            <?/*<div class="elSlider-item">
                <img src="images/slider1.jpg">
                <div class="it-inf">
                    <div class="it-title">depeche mode</div>
                    <div class="it-data"><i class="engIcon setIcon-date-white"></i>20.02.2016</div>
                    <div class="it-map"><i class="engIcon setIcon-map-white"></i>КЗ им. Ф.И. Шаляпина</div>
                    <div class="it-money"><i class="engIcon setIcon-price-white"></i>3500 - 5000 руб.</div>
                    <div class="it-btn">
                        <a href="" class="cssBorderRadius">Купить билет</a>
                    </div>
                </div>
            </div>*/?>
        </div>
    </div>

<?endif;?>
    <div class="engRow">
        <div class="elHeader-2 cssBg-red">
            <div class="engBox ">
                <div class="elHeader-search engBox-8 engPl">

                    <?$APPLICATION->IncludeComponent(
    "bitrix:search.form", 
    "search_line", 
    array(
        "USE_SUGGEST" => "N",
        "PAGE" => "#SITE_DIR#search/index.php",
        "COMPONENT_TEMPLATE" => "search_line"
    ),
    false
);?> 

                    <?/*<form id="elSearch">
                        <input id="elSearch-pole" class="cssBorderRadius-left cssLeft"  type="text" name="search" placeholder="Поиск концертов, мероприятий, исполнителей ..." autocomplete="off">
                        <button id="elSearch-btn" class="cssBorderRadius-right engBtn cssLeft">Найти</button>
                    </form>*/?>
                </div>
                <div class="elHeader-btn engBox-4 engPl cssText-center">
                    <a href="/halls/">10 Концертных залов</a>|
                    <a href="/events/">80 мероприятий</a>
                </div>
            </div>
        </div>
    </div>
</header>
