<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Корзина");

$APPLICATION->IncludeComponent('tim:empty', 'order');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
