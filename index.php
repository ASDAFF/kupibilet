<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "KupiBilet Online - сервис по продаже и бронированию билетов на все мероприятия и концерты, проводимые на КМВ. Заказывайте билеты онлайн на сайте или по телефону +7 (928) 335-65-65.");
$APPLICATION->SetPageProperty("keywords", "билеты онлайн, купить билет онлайн, билет на концерт");
$APPLICATION->SetPageProperty("title", "KupiBilet Online - продажа билетов на концерты и мероприятия КМВ онлайн");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Главная");

$APPLICATION->IncludeComponent('tim:empty', 'main_events');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
