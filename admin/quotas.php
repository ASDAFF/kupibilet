<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Редактирование квот и цен");
$APPLICATION->SetPageProperty("title", "Редактирование квот и цен");

$APPLICATION->IncludeComponent('tim:empty', 'quotas');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");