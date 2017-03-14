<footer class=" cssBg-red">
    <div class="elFooter">
        <div class="engBox">
            <div class="engBox-2 engPl-4 engMb-6">
                <div class="it-title">Меню</div>
                <div class="it-menu">
                    <?/*<ul>
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
                </div>
            </div>
            <div class="engBox-3 engPl-4 engMb-6">
                <div class="it-title">Как купить</div>
                <div class="it-menu">
                    <?/*<ul>
                        <li><a href="">Корпоративным клиентам</a></li>
                        <li><a href="">Возврат билетов</a></li>
                        <li><a href="">Подарочные карты</a></li>
                        <li><a href="">Как купить билет</a></li>
                        <li><a href="">Доставка билета</a></li>
                        <li><a href="">Правила использования падорочных карт</a></li>
                    </ul>*/?>
                    <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"main", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "buy",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "buy",
		"USE_EXT" => "N",
		"COMPONENT_TEMPLATE" => "main"
	),
	false
);?>
                </div>
            </div>
            <div class="engBox-4 engPl-4 engMb">
                <div class="it-title">ОРГАНИЗАТОРАМ</div>
                <div class="it-menu">
                    <ul>
                        <li>Контакты</li>
                    </ul>
                </div>
                <div class="it-menu">
                    <ul>
                        <li><span><i class="engIcon setIcon-15 setIcon-footer-map"></i>
                            <?$APPLICATION->IncludeFile(
                                SITE_DIR."/include/main/address.php",
                                array(),
                                array(
                                    "MODE" => "text"
                                )
                            ); ?>
                        	</span></li>
                        <li><span><i class="engIcon setIcon-15 setIcon-footer-phone"></i>
                        	<?$APPLICATION->IncludeFile(
								SITE_DIR."/include/main/tel.php",
								array(),
								array(
									"MODE" => "text"
								)
							); ?>
                                </span></li>
                        <li><span><i class="engIcon setIcon-15 setIcon-footer-mail"></i>
                        	<?$APPLICATION->IncludeFile(
								SITE_DIR."/include/main/mail.php",
								array(),
								array(
									"MODE" => "text"
								)
							); ?>
                            </span></li>
                    </ul>
                </div>
            </div>
            <div class="engBox-3 engPl engPl-css-center">
                <div class="it-title">Мы в сотсетях</div>
                <div class="it-menu set-soc">
                    <ul>
                        <?$APPLICATION->IncludeFile(
							SITE_DIR."/include/main/soc.php",
							array(),
							array(
								"MODE" => "html"
							)
						); ?>
                    </ul>
                </div>
                <div class="it-title">Оплата</div>
                <div class="it-menu set-cart">
                    <ul>
                        <li><i class="engIcon setIcon-45x31 setIcon-master"></i></li>
                        <li><i class="engIcon setIcon-45x31 setIcon-visa"></i></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="elFooter-down-full">
        <div class="engBox cssText-center">© 2017 ООО «KupiBilet» | 
        	<?$APPLICATION->IncludeFile(
				SITE_DIR."/include/main/mail.php",
				array(),
				array(
					"MODE" => "text"
				)
			); ?>
        </div>
    </div>
</footer>
<div id="engAjaxLog"></div>
</body>
</html>