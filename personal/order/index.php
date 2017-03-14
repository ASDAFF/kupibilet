<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle("Заказ оформлен");
$APPLICATION->SetPageProperty('title', "Заказ оформлен");

$APPLICATION->IncludeComponent('tim:empty', 'order', array(
	'PAGE' => 'complete',
));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
