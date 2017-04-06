<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

?><!DOCTYPE html>
<html lang="ru">

<?
/** @var CMain $APPLICATION */
/** @var CUser $USER */
?>

<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NBPJ5NR');</script>
<!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/images/favicon.png" rel="shortcut icon" type="image/x-icon">
    <title><? $APPLICATION->ShowTitle() ?></title>

    <?
    $assets = \Bitrix\Main\Page\Asset::getInstance();

    //	$assets->addCss('/js/jquery-ui/jquery-ui.min.css');
    $assets->addCss('/css/style.css');

    $assets->addJs('/js/jquery-2.1.4.min.js');
    $assets->addJs('/js/jquery-ui/jquery-ui.min.js');
    $assets->addJs('/js/jquery-ui/jquery.datepicker.extension.range.min.js');
    $assets->addJs('/js/owl/owl.carousel.min.js');
    //	$assets->addJs('/js/masonry.pkgd.min.js');
    $assets->addJs('/js/events.js');
    $assets->addJs('/js/timer.js');
    $assets->addJs('/js/scripts.js');

    $APPLICATION->ShowHead();
    ?>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NBPJ5NR"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<? $APPLICATION->ShowPanel(); ?>
<header class="elHeader">
    <div class="engBox">
        <div class="engBox-3 engPl-4 engPl-cssText-center engMb ">
            <div class="elHeader-logo">
                <a href="/" class="engIcon setIcon-logo"></a>
            </div>
        </div>
        <div class="engBox-4  engPl-none cssText-center cssVertical-center">
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
		<div class="engBox-2 engPl-4 engMb"><?
			$cartSummary = \Local\Sale\Cart::getSummary();
            ?>
            <div class="elCart">
                <div class="elCart-icon">
                    <svg class="engSvg set-45" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                        <path d="M 16 3.09375 L 15.28125 3.8125 L 7.09375 12 L 3 12 L 2 12 L 2 13 L 2 17 L 2 18 L 3 18 L 3.25 18 L 6.03125 27.28125 L 6.25 28 L 7 28 L 25 28 L 25.75 28 L 25.96875 27.28125 L 28.75 18 L 29 18 L 30 18 L 30 17 L 30 13 L 30 12 L 29 12 L 24.90625 12 L 16.71875 3.8125 L 16 3.09375 z M 16 5.9375 L 22.0625 12 L 9.9375 12 L 16 5.9375 z M 4 14 L 28 14 L 28 16 L 27.25 16 L 27.03125 16.71875 L 24.25 26 L 7.75 26 L 4.96875 16.71875 L 4.75 16 L 4 16 L 4 14 z M 11 17 L 11 24 L 13 24 L 13 17 L 11 17 z M 15 17 L 15 24 L 17 24 L 17 17 L 15 17 z M 19 17 L 19 24 L 21 24 L 21 17 L 19 17 z"></path>
                    </svg>
                    <i class="engCircle" id="current_cart_count"><?=$cartSummary['COUNT']?></i>
                </div>
                <div class="elCart-title">Ваша корзина</div>
                <div class="elCart-inf">
                    <a href="/personal/cart/" title="Перейти в корзину">
                        Сумма: <span id="current_cart_price"><?=$cartSummary['PRICE'];?></span> руб.</a>
                </div>
	            <div style="text-align: right;width: 79%;font-size: 11px;line-height: 11px;" id="reserve_timer"
	                 data-expired="<?= $cartSummary['EXPIRED'] ?>"></div>
            </div>
		</div>
        <div class="engBox-3 engPl-4 engMb">
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
            <div class="elHeader-user cssText-right engPl-cssText-center"><?
	            if ($USER->IsAuthorized())
	            {
		            ?>
		            <a href="/personal/order/history/" class="cssBorderRadius">Заказы</a>
		            <a href="/?logout=yes" class="cssBorderRadius">Выход</a><?
	            }
	            else
	            {
		            ?>
		            <a href="/login/" class="cssBorderRadius">Вход</a>
		            <a href="/login/?register=yes" class="cssBorderRadius">Регистрация</a><?
	            }
	            ?>
            </div>
        </div>
    </div>
    <div class="elHeader-menu-full cssBg-red">
        <nav class="elHeader-menu engBox" id="engNav"><?

	        $APPLICATION->IncludeComponent("bitrix:menu", "main", Array(
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
			);

	        ?>
            <a id="engNav-btn">Меню</a>
        </nav>
    </div><?

	if (!Funcs::$uri[0])
	{
		?>
	    <div class="engBox">
	        <div class="elSlider">
	            <?$APPLICATION->IncludeComponent('tim:empty', 'main_banners');?>
	        </div>
	    </div><?
	}

	$halls = \Local\Main\Hall::getAll();
	$hallsCount = count($halls['ITEMS']);
	$hallsText = \Local\System\Utils::cardinalNumberRus($hallsCount, 'залов', 'зал', 'зала');
	$hallsText = $hallsCount . ' концертных ' . $hallsText;

	$events = \Local\Main\Event::getAll();
	$eventsCount = count($events);
	$eventsText = \Local\System\Utils::cardinalNumberRus($eventsCount, ' мероприятий', 'мероприятие', 'мероприятия');
	$eventsText = $eventsCount . $eventsText;
	?>
    <div class="engRow">
        <div class="elHeader-2 cssBg-red">
            <div class="engBox ">
                <div class="elHeader-search engBox-8 engPl">
                    <form id="elSearch" action="/event/">
                        <input id="elSearch-pole" class="cssBorderRadius-left cssLeft" type="text" name="q"
                               placeholder="Поиск концертов, мероприятий ..." autocomplete="off"
                               value="<?= $_REQUEST['q'] ?>" />
                        <button id="elSearch-btn" class="cssBorderRadius-right engBtn cssLeft">Найти</button>
                    </form>
                </div>
                <div class="elHeader-btn engBox-4 engPl cssText-center">
                    <a href="/halls/"><?= $hallsText ?></a>|
                    <a href="/event/"><?= $eventsText ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
</header>