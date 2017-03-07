<!DOCTYPE html>
<html lang="ru">

<?
/** @var CMain $APPLICATION */
/** @var CUser $USER */
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><? $APPLICATION->ShowTitle() ?></title>

    <?
    $assets = \Bitrix\Main\Page\Asset::getInstance();

	$assets->addCss('/js/fancybox/jquery.fancybox.css');
	$assets->addCss('/js/jquery-ui/jquery-ui.css');
	$assets->addCss('/css/style.css');

	$assets->addJs('/js/jquery-2.1.4.min.js');
	$assets->addJs('/js/jquery-ui/jquery-ui.js');
    $assets->addJs('/js/jquery-ui/jquery.datepicker.extension.range.min.js');
	$assets->addJs('/js/fancybox/jquery.fancybox.js');
	$assets->addJs('/js/masonry.pkgd.min.js');
	$assets->addJs('/js/events.js');
	$assets->addJs('/js/scripts.js');

    $APPLICATION->ShowHead();
    ?>
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
                <? if (!$USER->IsAuthorized()):?>
                    <a href="/login/" class="cssBorderRadius">Вход</a>
                    <a href="/login/?register=yes" class="cssBorderRadius">Регистрация</a>
                <?else:?>
                    тут выводим кнопки для авторизованного пользователя
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
    </div>

<?if(!Funcs::$uri[0]):?>
    <div class="engBox">
        <div class="elSlider">
            <?$APPLICATION->IncludeComponent('tim:empty', 'banner');?>

            <?/*$APPLICATION->IncludeComponent(
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
                        "section" => "#SECTION_CODE_PATH#/",
                        "detail" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
                    )
                ),
                false
            );*/?>
        </div>
    </div>
<?endif;?>


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
                    <a href="/halls/">10 Концертных залов</a>|
                    <a href="/event/">80 мероприятий</a>
                </div>
            </div>
        </div>
    </div>
</div>
</header>
