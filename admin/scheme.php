<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Конструктор схемы мест");
$APPLICATION->SetPageProperty("title", "Конструктор схемы мест");

$APPLICATION->IncludeComponent('tim:empty', 'scheme');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");