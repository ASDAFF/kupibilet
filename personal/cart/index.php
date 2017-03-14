<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Корзина");
$APPLICATION->SetPageProperty('title', "Корзина");

$APPLICATION->IncludeComponent('tim:empty', 'cart');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
