<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Главная");

$APPLICATION->IncludeComponent('tim:empty', 'main_events');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
