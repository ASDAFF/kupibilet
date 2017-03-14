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
        <div class="engBox-3 engPl-6 engPl-cssText-center engMb ">
            <div class="elHeader-logo">
                <a href="/" class="engIcon setIcon-logo"></a>
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
                <? if (!$USER->IsAuthorized()):?>
                    <a href="/login/" class="cssBorderRadius">Вход</a>
                    <a href="/login/?register=yes" class="cssBorderRadius">Регистрация</a>
                <?else:?>
                    <a href="/personal/" class="cssBorderRadius">Личный кабинет</a>
                    <a href="/?logout=yes" class="cssBorderRadius">Выход</a>
                <?endif;?>
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
