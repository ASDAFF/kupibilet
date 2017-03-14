<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("История заказов");
$APPLICATION->SetPageProperty('title', "История заказов");

$APPLICATION->IncludeComponent('tim:empty', 'order_history');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
